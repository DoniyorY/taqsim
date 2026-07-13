<?php

namespace frontend\modules\api\controllers;

use common\models\Client;
use common\models\ClientCards;
use common\models\CreditPlan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\db\Query;
use common\models\Credit;

/**
 * @package frontend\modules\api\controllers
 * @class AlgenixController
 * @extends yii\rest\Controller
 *
 * Контроллер отвечает за интеграцию с внешней системой Algenix.
 * Он реализует методы для:
 *  - получения задолженности по конкретному договору;
 *  - фиксации успешных оплат;
 *  - получения списка должников по филиалам (с пагинацией);
 *  - а также вспомогательные методы для отправки POST-запросов в API Algenix.
 *
 * Каждый метод логирует обращение в таблицу `algenix_logs` для последующего аудита.
 *
 * Авторизация реализована через Bearer токен (SimpleBearerAuth).
 */
class AlgenixController extends Controller
{
    /**
     * @inheritdoc
     * Определяет поведение контроллера:
     *  - Формат ответа: JSON;
     *  - Аутентификация: Bearer (SimpleBearerAuth);
     *  - Разрешённые HTTP-методы для действий.
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => SimpleBearerAuth::class,
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'get-contract-dept' => ['POST'],
                'get-payment-response' => ['POST'],
                'get-debtors' => ['get'],
            ],
        ];
        return $behaviors;
    }


    /**
     * Получение задолженности по договору.
     *
     * @route POST /api/algenix/get-contract-debt
     *
     * @request
     * {
     *   "external_id": 12345
     * }
     *
     * @return array JSON:
     * {
     *   "success": true,
     *   "result": {
     *     "status": true,
     *     "external_id": 12345,
     *     "debt": 125000.00
     *   },
     *   "error": null
     * }
     *
     * @throws HttpException 404 если договор не найден.
     *
     * Логирует запрос и возвращает сумму всех неоплаченных планов по договору.
     */
    public function actionGetContractDebt()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $res = $this->getPost(['external_id']);

        $external_id = (int)$res['external_id'];
        $algenix_log = new \common\models\AlgenixLogs([
            'created' => time(),
            'amount' => 0,
            'action' => 'actionGetContractDebt',
            'content' => "Запрос информации о договоре: $external_id",
            'ip' => Yii::$app->request->getRemoteIP() ?? 0,
            'req' => json_encode($_SERVER) ?? null
        ]);
        $algenix_log->save(false);
        /** @var Credit|null $credit */
        $credit = Credit::findOne($external_id);
        if (!$credit || $credit->id == 32442) {
            $res = [
                'success' => false,
                'result' => [
                    'status' => false,
                ],
                'error' => [
                    'errorCode' => '404',
                    'errorMessage' => 'Договор не найден'
                ]
            ];
            return $res;
        }

        $plans = \common\models\CreditPlan::find()
            //->where(['pay_status' => 0])
            ->andWhere(['credit_id' => $credit->id])
            ->andWhere(['<', 'created', time()])
            ->all();
        $payment = \common\models\Payments::find()->where(['credit_id' => $credit->id])->sum('amount');
        $ost = 0;
        foreach ($plans as $plan) {
            $ost += $plan->pay_summa;
        }
        $ost = (int)$ost - (int)$payment;
        $result = [
            'status' => true,
            'external_id' => $credit->id,
            'debt' => $ost,
        ];

        return ['success' => true, 'result' => $result, 'error' => null];
    }


    /**
     * Обработка уведомления об оплате из Algenix.
     *
     * @route POST /api/algenix/get-payment-response
     *
     * @request
     * {
     *   "external_id": 12345,
     *   "amount": 100000
     * }
     *
     * @return array JSON:
     * {
     *   "success": true,
     *   "result": {
     *     "status": true,
     *     "external_id": "12345",
     *     "debt": 25000.0
     *   },
     *   "error": null
     * }
     *
     * Если договор найден, логирует успешную оплату и возвращает текущий остаток долга.
     */
    public function actionGetPaymentResponse()
    {
        $res = $this->getPost(['external_id', 'amount', 'transaction_id']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $credit = Credit::findOne(['id' => (int)$res['external_id']]);
            if (!$credit) {
                return [
                    'success' => false,
                    'result' => [
                        'status' => false,
                    ],
                    'error' => [
                        'errorCode' => 404,
                        'errorMessage' => 'Договор не найден'
                    ]
                ];
            }

            $plans = CreditPlan::find()
                ->where(['credit_id' => $credit->id, 'pay_status' => [0, 4, 5, 6]])
                ->andWhere(['<', 'created', time()])->orderby(['id' => SORT_ASC])->all();

            $amountLeft = $res['amount'];
            $totalPaidNow = 0;                      // сколько оплатили в этом запросе
            foreach ($plans as $p) {

                if ($amountLeft <= 0) {
                    break; // деньги закончились – выходим
                }

                $alreadyPaid = \common\models\Payments::find()
                    ->where(['credit_plan_id' => $p->id])
                    ->sum('amount');

                // Остаток по плану
                $planRest = $p->pay_summa - $alreadyPaid;

                // Если по каким-то причинам план уже закрыт суммой, а статус не обновлён
                /*if ($planRest <= 0) {
                    if ((int)$p->pay_status !== 1) {
                        $p->pay_status = 1;
                        $p->save(false);
                    }
                    continue;
                }*/

                // Сколько платим по этому плану сейчас
                $payNow = min($planRest, $amountLeft);
                if ($payNow <= 0) {
                    continue;
                }

                $payment = new \common\models\Payments([
                    'created' => time(),
                    'payment_type' => 0,
                    'method_id' => 3,
                    'pay_type' => 1,
                    'company_id' => $credit->company_id,
                    'content' => "Плановая оплата по договору №{$credit->id} - " . date('d.m.Y', $credit->created)
                        . " Оплата через Algenix | Дата плана: " . date('d.m.Y', $p->created),
                    'credit_plan_id' => $p->id,
                    'user_id' => 10,
                    'credit_id' => $credit->id,
                    'credit_type_id' => $credit->credit_type_id,
                    'amount' => $payNow,
                ]);
                $payment->save(false);

                $amountLeft -= $payNow;
                $totalPaidNow += $payNow;

                // Если план закрыт текущей оплатой – отмечаем
                if ($planRest - $payNow <= 0) {
                    $p->pay_status = 1;
                    $p->save(false);
                }

            }
            $totalPlanSum = \common\models\CreditPlan::find()
                ->where(['credit_id' => $credit->id])
                ->andWhere(['<', 'created', time()])
                ->sum('pay_summa');

            $totalPaid = \common\models\Payments::find()
                ->where(['credit_id' => $credit->id])
                ->andWhere(['<', 'created', time()])
                ->sum('amount');

            $debt = $totalPlanSum - $totalPaid;
            if ($debt < 0) {
                $debt = 0;
            }
            $algenix_log = new \common\models\AlgenixLogs([
                'created' => time(),
                'amount' => $res['amount'],
                'action' => 'actionGetPaymentResponse',
                'content' => 'Успешная оплата Algenix за договор №' . $credit->id,
                'ip' => Yii::$app->request->getRemoteIP() ?? 0,
                'req' => json_encode($_SERVER) ?? null,
                'transaction_id' => $res['transaction_id'] ?? null
            ]);
            $algenix_log->save(false);
            $transaction->commit();
            return [
                'success' => true,
                'result' => [
                    'status' => true,
                    'external_id' => (string)$credit->id,
                    'debt' => (float)$debt,
                ],
                'error' => null
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            $algenix_log = new \common\models\AlgenixLogs([
                'created' => time(),
                'amount' => $res['amount'],
                'action' => 'actionGetPaymentResponse',
                'content' => 'Ошибка оплаты Algenix за договор №' . $credit->id . ' || ' . $e,
                'ip' => Yii::$app->request->getRemoteIP() ?? 0,
                'req' => json_encode($_SERVER) ?? null,
                'transaction_id' => $res['transaction_id'] ?? null
            ]);
            $algenix_log->save(false);
        }

    }

    /**
     * Получение списка должников по филиалу.
     *
     * @route POST /api/algenix/get-debtors?per-page=50&page=1
     *
     * @request
     * {
     *   "branch_id": 7
     * }
     *
     * @return array JSON:
     * {
     *   "meta": {
     *     "current_page": 1,
     *     "pageCount": 5,
     *     "total": 234,
     *     "pageSize": 50
     *   },
     *   "debtors": [
     *     {
     *       "client": {
     *         "pinfl": "40802694200081",
     *         "first_name": "Махбубахон",
     *         "middle_name": "Холдаровна",
     *         "last_name": "Бурханова",
     *         "born_date": "1969-02-08",
     *         "passport_series": "AD",
     *         "passport_number": "8001078",
     *         "passport_date": "2029-02-08"
     *       },
     *       "contracts": [
     *         {"contract_id": 202820, "debt": 150000.00},
     *         {"contract_id": 202821, "debt": 90000.00}
     *       ]
     *     }
     *   ]
     * }
     *
     * @note
     * Пагинация управляется параметром `limit` (по умолчанию 50, максимум 500).
     * Метод использует ActiveDataProvider для оптимизации запроса.
     */
    public function actionGetDebtors()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $res = Yii::$app->request->get();
        $limit = (int)Yii::$app->request->get('limit', 50);
        $limit = min(max($limit, 1), 500); // не меньше 1 и не больше 500
        // логирование
        $algenix_log = new \common\models\AlgenixLogs([
            'created' => time(),
            'amount' => 0,
            'action' => 'actionGetDebtors',
            'content' => "Запрос информации о договорах по филиалу: {$res['branch_id']}",
            'ip' => Yii::$app->request->getRemoteIP() ?? 0,
            'req' => json_encode($_SERVER) ?? null
        ]);
        $algenix_log->save(false);

        // сам запрос
        $query = (new Query())
            ->select([
                'c.id AS credit_id',
                'c.doc_total_price',
                'cl.fullname',
                'cl.passport_pinfl',
                'cl.passport_numb',
                'cl.birthday',
                'cl.passport_enddate',
                'COALESCE(SUM(cp.pay_summa),0) AS plan_summa',
                //  'COALESCE(SUM(p.amount),0) as pay_summa'
            ])
            ->from('credit c')
            ->innerJoin('credit_plan cp', 'cp.credit_id=c.id')
            ->innerJoin('client cl', 'c.client_id=cl.id')
            //->leftJoin('payments p', 'p.credit_plan_id=cp.id')
            ->andWhere(['c.company_id' => $res['branch_id'], 'c.credit_status' => 2])
            ->andWhere(['c.algenix_autopay' => 1])
            ->groupBy('c.id');

        // провайдер с пагинацией

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $limit,
                'pageSizeParam' => 'per-page',
                'pageParam' => 'page',
            ],
        ]);

        // получаем модели
        $models = $provider->getModels();

        $debtors = [];
        foreach ($models as $item) {
            $fio = $this->splitFullName($item['fullname']);
            $clientKey = $item['passport_pinfl'];
            $plans = \common\models\CreditPlan::find()->where(['credit_id' => $item['credit_id']])->andWhere(['<', 'created', time()])->sum('pay_summa');

            if (!isset($debtors[$clientKey])) {
                $passport = $this->splitPassport($item['passport_numb']);
                $debtors[$clientKey] = [
                    'client' => [
                        'pinfl' => $item['passport_pinfl'],
                        'first_name' => $fio['first_name'],
                        'middle_name' => $fio['middle_name'],
                        'last_name' => $fio['last_name'],
                        'born_date' => $item['birthday'],
                        'passport_series' => $passport['series'],
                        'passport_number' => $passport['number'],
                        'passport_date' => $item['passport_enddate'],
                    ],
                    'contracts' => [],
                ];
            }
            (float)$payment = \common\models\Payments::find()->where(['credit_id' => $item['credit_id']])->sum('amount');
            $debtors[$clientKey]['contracts'][] = [
                'contract_id' => (int)$item['credit_id'],
                'debt' => (float)$plans - $payment,
            ];
        }

        // пагинация возвращает текущую и общее число страниц
        $pagination = $provider->getPagination();
        if ($pagination->getPageCount() < Yii::$app->request->get('page')) {
            $debtors = [];
        }

        return [
            'meta' => [
                'current_page' => $pagination->page + 1,
                'pageCount' => $pagination->getPageCount(),
                'total' => $provider->getTotalCount(),
                'limit' => $pagination->pageSize,
            ],
            'debtors' => array_values($debtors),
        ];
    }


    /**
     * Отправка данных об оплате в API Algenix.
     *
     * @param int $credit_id ID кредита (external_id)
     * @param int $amount Сумма оплаты
     * @param string $content Комментарий
     * @return true Ответ от API Algenix
     *
     * @note Используется Basic Auth с тестовыми данными ("login:password").
     * @todo Вынести URL и креды в настройки приложения.
     */
    public static function makeCreditPayment($credit_id, $amount, $content)
    {

        $url = 'https://yesgilam-api.algenix.uz/api/software/postPayment'; // TODO: заменить {} на реальный URL

        $data = [
            'external_id' => (string)$credit_id,
            'amount' => (int)$amount,
            'commentary' => $content,
        ];
        $send = self::sendAlgenix($url, $data);
        \common\components\AlgenixLogger::log("Прошла операция ALGENIX \n", [
            'credit_id' => (string)$credit_id,
            'amount' => $amount,
            'content' => $content,
            'created' => date('d.m.Y'),
            'data' => $send
        ]);
        return true;
    }

    public static function addCard($client_id, $card)
    {
        $url = "https://yesgilam-api.algenix.uz/api/software/createCard";

        try {
            $client = Client::findOne($client_id);
            $str = str_replace(' ', '', $card->card_number);
            [$month, $year] = explode('/', $card->card_date);
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            $expiry_formatted = sprintf("%s%s", $year, $month);
            $parts = preg_split('/\s+/', trim($client->fullname));

            if (count($parts) !== 3) {
                return [
                    'success'=>false,
                    'message'=>'Введите ФИО в формате: Фамилия Имя Отчество.',
                ];
            }
            [$last_name, $first_name, $middle_name] = explode(' ', $client->fullname);
            $passport_given_date = date('Y-m-d', strtotime("-10 year", strtotime($client->passport_enddate)));
            $credit = Credit::find()->where(['client_id' => $client->id])->orderBy(['id' => SORT_DESC])->one();
            $data = [
                'client' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'middle_name' => $middle_name,
                    'born_date' => $client->birthday,
                    'tin' => $client->passport_pinfl,
                    'passport' => $client->passport_numb,
                    'passport_given_date' => $passport_given_date,
                ],
                'card' => [
                    'number' => $str,
                    'expire_date' => $expiry_formatted,
                    'phone_number' => $client->phone
                ],
                'branch_id' => ($credit) ? $credit->company_id : 1
            ];
            $res = self::sendAlgenix($url, $data, Yii::$app->params['algenix_card_token']);
            $encoded = json_encode($res);
            $algenix_log = new \common\models\AlgenixLogs([
                'created' => time(),
                'amount' => 0,
                'action' => 'actionAddCard',
                'content' => "Привязка карты: $card->id, Данные: \{$encoded\}",
                'ip' => Yii::$app->request->getRemoteIP() ?? 0,
                'req' => json_encode($_SERVER) ?? null
            ]);
            $algenix_log->save(false);
            if (array_key_exists('errors', $res)) {
                return [
                    'success' => false,
                    'data' => $res,
                ];
            }
            return [
                'success' => true,
                'data' => $res,
            ];

        } catch (\Exception $e) {
            new HttpException(500,"{$e->getMessage()} ALgenix");
        }
    }

    public static function confirmCard($post)
    {
        $url = "https://yesgilam-api.algenix.uz/api/software/confirmCard";
        $data = [
            'session' => $post['session_id'],
            'otp' => $post['otp'],
            'client_id' => $post['client_id'],
            'branch_id' => $post['branch_id'],
            'phone_number' => $post['phone_number'],
        ];
        $res = self::sendAlgenix($url, $data, Yii::$app->params['algenix_card_token']);
        $encoded = json_encode($res);
        $algenix_log = new \common\models\AlgenixLogs([
            'created' => time(),
            'amount' => 0,
            'action' => 'actionConfirmCard',
            'content' => "Подтверждение номера телефона клиента: {$data['client_id']} \{$encoded\}",
            'ip' => Yii::$app->request->getRemoteIP() ?? 0,
            'req' => json_encode($_SERVER) ?? null
        ]);
        $algenix_log->save(false);
        if (array_key_exists('error', $res)) {
            return ['success' => false, 'data' => $res];
        }
        return ['success' => true, 'data' => $res];
    }

    public static function attachCard($client_id, $credit_id, $card_id, $status)
    {
        $url = "https://yesgilam-api.algenix.uz/api/software/attachContactCard";
        try {
            $client = Client::findOne($client_id);
            $credit = Credit::findOne($credit_id);
            $card = ClientCards::findOne($card_id);
            [$last_name, $first_name, $middle_name] = explode(' ', $client->fullname);
            $passport_given_date = date('Y-m-d', strtotime("-10 year", strtotime($client->passport_enddate)));
            $data = [
                'client' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'middle_name' => $middle_name,
                    'born_date' => $client->birthday,
                    'tin' => $client->passport_pinfl,
                    'passport' => $client->passport_numb,
                    'passport_given_date' => $passport_given_date,
                ],
                'attach' => (bool)$status,
                'client_card_ids' => [$card->algenix_card_id],
                'contracts' => [$credit->id],
            ];
            $res = self::sendAlgenix($url, $data, Yii::$app->params['algenix_card_token']);
            if (array_key_exists('error', $res)) {
                return ['success' => false, 'data' => $res];
            }
            return ['success' => true, 'data' => $res];
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            die();
        }
    }


    /**
     * Отправка POST-запроса в Algenix API.
     *
     * @param string $url URL конечной точки API
     * @param array $data Данные запроса
     * @param string $token Токен ключ Algenix
     * @return array Ответ от API или сообщение об ошибке.
     */

    private static function sendAlgenix($url, $data, $token = null)
    {

        $response = self::getClient()->post($url, json_encode($data), [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Basic " . base64_encode($token ?? Yii::$app->params['algenixAuth']),
        ])->send();
        if ($response) {
            return $response->data;
        }

        return [
            'success' => false,
            'error' => 'Ошибка при выполнении запроса к API Algenix',
            'status' => $response->statusCode,
        ];
    }

    /**
     * Возвращает экземпляр HTTP-клиента.
     *
     * @return \yii\httpclient\Client
     */
    private static function getClient()
    {
        return new \yii\httpclient\Client();
    }

    /**
     * Извлекает POST-данные из тела запроса (JSON).
     *
     * @param array $ps Массив обязательных параметров (опционально).
     * @return array Массив данных из POST-запроса.
     * @throws HttpException 400 если параметр отсутствует.
     */
    private function getPost($ps = array())
    {
        $post = json_decode(Yii::$app->request->getRawBody(), true);
        if (!$post) {
            throw new HttpException(400, 'Параметры не переданы');
        }
        if ($ps) {
            foreach ($ps as $value) {
                if ($value && !array_key_exists($value, $post) || !$post[$value]) {
                    throw new HttpException(400, 'Отсутствуют обязательные параметры: / ' . $value);
                }
            }
        }
        return $post;
    }

    /**
     * Разделяет полное ФИО на фамилию, имя, отчество.
     *
     * @param string $fullname Полное имя, например "Иванов Иван Иванович".
     * @return array [
     *   'last_name' => 'Иванов',
     *   'first_name' => 'Иван',
     *   'middle_name' => 'Иванович'
     * ]
     */
    private function splitFullName($fullname)
    {
        $parts = preg_split('/\s+/', trim($fullname));
        return [
            'last_name' => $parts[0] ?? '',
            'first_name' => $parts[1] ?? '',
            'middle_name' => $parts[2] ?? '',
        ];
    }

    /**
     * Разделяет паспорт на серию и номер.
     *
     * @param string $passport Например: "AD1234567"
     * @return array [
     *   'series' => 'AD',
     *   'number' => '1234567'
     * ]
     */
    private function splitPassport($passport)
    {
        // Убираем пробелы, если вдруг есть
        $passport = trim($passport);

        // Ищем: сначала буквы, потом цифры
        if (preg_match('/^([A-Za-zА-Яа-яЁё]+)([0-9]+)$/u', $passport, $m)) {
            return [
                'series' => strtoupper($m[1]),
                'number' => $m[2],
            ];
        }

        // Если не совпало — возвращаем пустые значения
        return [
            'series' => '',
            'number' => '',
        ];
    }
}
