<?php

namespace frontend\modules\api\controllers;

use common\models\Company;
use common\models\CreditPlan;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\db\Query;
use common\models\Credit;

/**
 * Контроллер для интеграции с системой Algenix.
 * Содержит методы для получения кредитных данных и взаимодействия с API Algenix.
 */
class AlgenixController extends Controller
{

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
                'get-debtors' => ['post'],
            ],
        ];
        return $behaviors;
    }

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
        if (!$credit) {
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
            ->where(['pay_status' => 0])
            ->andwhere(['credit_id' => $credit->id])
            ->andWhere(['<', 'created', time()])
            ->all();
        $ost = 0;
        foreach ($plans as $plan) {
            $ost += $plan->pay_summa;
        }
        $result = [
            'status' => true,
            'external_id' => $credit->id,
            'debt' => $ost,
        ];

        return ['success' => true, 'result' => $result, 'error' => null];
    }

    public function actionGetPaymentResponse()
    {
        $res = $this->getPost(['external_id', 'amount']);
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
        $algenix_log = new \common\models\AlgenixLogs([
            'created' => time(),
            'amount' => $res['amount'],
            'action' => 'actionGetPaymentResponse',
            'content' => 'Успешная оплата Algenix за договор №' . $credit->id,
            'ip' => Yii::$app->request->getRemoteIP() ?? 0,
            'req' => json_encode($_SERVER) ?? null
        ]);
        $algenix_log->save(false);
        $plans = CreditPlan::findAll(['credit_id' => $credit->id, 'pay_status' => [0,4,5,6]]);
        $ost = 0;
        foreach ($plans as $p) {
            $ost += $p->pay_summa;
        }
        return [
            'success' => true,
            'result' => [
                'status' => true,
                'external_id' => (string)$credit->id,
                'debt' => (float)$ost,
            ],
            'error' => null
        ];
    }

    public function actionGetDebtors()
    {
        $res = $this->getPost(['branch_id']);
        $algenix_log = new \common\models\AlgenixLogs([
            'created' => time(),
            'amount' => 0,
            'action' => 'actionGetDebtors',
            'content' => "Запрос информации о договорах по филиалу: {$res['branch_id']}",
            'ip' => Yii::$app->request->getRemoteIP() ?? 0,
            'req' => json_encode($_SERVER) ?? null
        ]);
        $algenix_log->save(false);
        $query = (new Query())->select([
            'c.id as credit_id',
            'c.doc_total_price',
            'cl.fullname',
            'cl.passport_pinfl',
            'cl.passport_numb',
            'cl.birthday',
            'cl.passport_enddate',
            'COALESCE(SUM(cp.pay_summa),0) as plan_summa',
        ])
            ->from('credit c')
            ->innerJoin('credit_plan cp', 'cp.credit_id=c.id AND cp.pay_status=0')
            ->innerJoin('client cl', 'c.client_id=cl.id')
            ->andWhere(['c.company_id' => $res['branch_id'], 'c.credit_status' => 2])
            ->groupBy('c.id')
            ->all();

        $debtors = [];
        foreach ($query as $item) {
            $fio = $this->splitFullName($item['fullname']);

            $clientKey = $item['passport_pinfl']; // можно использовать pinfl как уникальный идентификатор

            if (!isset($debtors[$clientKey])) {
                $passport = $this->splitPassport($item['passport_numb']);
                $debtors[$clientKey] = [
                    'client' => [
                        'pinfl'           => $item['passport_pinfl'],
                        'first_name'      => $fio['first_name'],
                        'middle_name'     => $fio['middle_name'],
                        'last_name'       => $fio['last_name'],
                        'born_date'       => $item['birthday'],
                        'passport_series' => $passport['series'],
                        'passport_number' => $passport['number'],
                        'passport_date'   => $item['passport_enddate'],
                    ],
                    'contracts' => [],
                ];
            }

            $debtors[$clientKey]['contracts'][] = [
                'contract_id' => (int)$item['credit_id'],
                'debt'        => (float)$item['plan_summa'],
            ];
        }
        return ['debtors' => array_values($debtors)];

    }
    private function splitFullName($fullname)
    {
        $parts = preg_split('/\s+/', trim($fullname));
        return [
            'last_name'   => $parts[0] ?? '',
            'first_name'  => $parts[1] ?? '',
            'middle_name' => $parts[2] ?? '',
        ];
    }
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

    /**
     * Отправляет данные об оплате кредита в Algenix.
     *
     * @param int $credit_id ID кредита
     * @param string $amount Сумма оплаты
     * @param string $content Комментарий
     * @return array|mixed Ответ API
     */
    public static function makeCreditPayment($credit_id, $amount, $content)
    {

        $url = 'https://staging-api.algenix.uz/api/software/postPayment'; // TODO: заменить {} на реальный URL

        $data = [
            'external_id' => $credit_id,
            'amount' => (int)$amount,
            'commentary' => $content,
        ];

        return self::sendAlgenix($url, $data);
    }

    /**
     * Универсальный метод отправки POST-запросов в Algenix API.
     *
     * @param string $url URL API
     * @param array $data Данные запроса
     * @return array Ответ API
     */

    private static function sendAlgenix($url, $data)
    {

        $response = self::getClient()->post($url, json_encode($data), [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
            'Authorization' => "Basic " . base64_encode('yesgilam:yesgilam'),
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
}
