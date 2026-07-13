<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\CreditPlan;
use common\models\Credit;
use common\models\CreditCards;
use common\models\Client;
use common\models\ClientCards;


/**
 * AtmosController for API transaction of Atmos | https://docs.atmos.uz
 */
class AtmosController extends Controller
{

    public function actionConnectionTest()
    {
        return self::getToken();
    }

    public function actionStartTest()
    {
        $startPinflTransaction = self::startPinflTransaction(20145);
        return $this->asJson(json_decode($startPinflTransaction));
    }

    /*public function actionStartTransaction()
    {
        $data = [
            'amount' => (56000),
            'account' => "19464/184046",
            'store_id' => \Yii::$app->params['store_id'],
            'lang' => 'ru',
        ];
        return self::startTransaction($data);
    }*/

    public function actionCheckTest()
    {
        echo "<pre>";
        return self::checkPinflTransaction(184252);
    }

    public function actionConfirmTest()
    {
        $confirmPinflTransaction = self::confirmPinflTransaction('1887471719224678', '663d1797-036c-3e5b-8542-77e5a4c572c8');
        var_dump($confirmPinflTransaction);
        die();
        return $confirmPinflTransaction;
    }

    /**
     * Метод для начала привязки карты
     * @param $card_number int 16 значный пин карты
     * @param $card_expiry int срок действия карты
     * @return array|mixed|string
     */
    public static function addCard($card_number, $card_expiry)
    {
        // Формирование данных для запроса
        $data = [
            'card_number' => $card_number,
            'expiry' => $card_expiry
        ];

        // URL для запроса
        $url = 'https://partner.atmos.uz/partner/bind-card/init';

        // Отправка запроса и получение ответа
        $response = self::sendAtmos($url, $data);

        // Проверка на наличие 'transaction_id' в ответе
        if (isset($response->data['transaction_id'])) {
            // Если ключ 'transaction_id' присутствует, возвращаются данные
            return [
                'transaction_id' => $response->data['transaction_id'],
                'phone' => $response->data['phone'] ?? null
            ];
        }

        // Если ключ 'transaction_id' отсутствует, возвращается описание ошибки
        return $response->data['result']['description'] ?? 'Unknown error occurred';
    }

    /**
     * Метод для аннулирования банковской карты из системы
     * @param $id int atmos_card_id уникальный номер сохраненный от Atmos
     * @param $token string card_token уникальный токен сохраненный от Atmos
     * @return bool|void
     */
    public static function disableCard($id, $token)
    {
        // URL для запроса
        $url = 'https://partner.atmos.uz/partner/remove-card';

        // Формирование данных для запроса
        $data = [
            'id' => $id,
            'token' => $token
        ];

        // Отправка запроса и получение ответа
        $response = self::sendAtmos($url, $data);

        // Проверка наличия данных в ответе
        if ($response->data['data']) {
            // Если данные присутствуют, значит карта успешно отключена
            return true;
        } elseif ($response->data['result']['code'] == 'STPIMS-ERR-004') {
            // Если код ошибки соответствует определенному значению, карта не найдена или уже отключена
            return false;
        }
    }

    /**
     * Метод для подтверждения номера телефона для добавленной карты
     * @param $t int transaction_id полученный с метода addCard
     * @param $c int otp код отправленный с Atmos
     * @return array
     */
    public static function confirmPhone($t, $c)
    {
        // URL для запроса
        $url = 'https://partner.atmos.uz/partner/bind-card/confirm';

        // Формирование данных для запроса
        $data = [
            'transaction_id' => $t,
            'otp' => $c
        ];

        // Отправка запроса и получение ответа
        $response = self::sendAtmos($url, $data);

        // Проверка кода результата
        if ($response->data['result']['code'] == 'STPIMS-ERR-098' or $response->data['result']['code'] == 'STPIMS-ERR-080') {
            // Если код ошибки соответствует определенным значениям, возвращается ошибка
            return [
                'code' => 'error',
                'description' => $response->data['result']['description']
            ];
        }

        // В противном случае возвращаются номер телефона, токен карты и идентификатор карты
        return [
            'phone' => $response->data['data']['phone'],
            'card_token' => $response->data['data']['card_token'],
            'card_id' => $response->data['data']['card_id'],
        ];
    }

    /**
     * Метод для оформления транзакции
     * @param $data array Массив с парамертами
     * @return array|mixed
     */
    public static function startTransaction($data)
    {
        // URL для запроса
        $url = 'https://partner.atmos.uz/merchant/pay/create';

        // Отправка запроса и получение ответа
        $res = self::sendAtmos($url, $data);
        $data = $res->data;
        $store = $data['store_transaction'];

        // Проверка кода результата
        if ($data['result']['code'] == 'OK') {
            // Если код результата успешный, возвращаются данные транзакции
            return [
                'trans_id' => $store['trans_id'],
                'account' => $store['account'],
                'amount' => $store['amount'],
                'label' => $store['label'],
            ];
        }
        // В противном случае возвращается описание ошибки
        return $data['result']['description'];
    }

    /**
     * Предподтверждение транзакции
     *
     * @param $trans_id int параметр полученный после успешного startTransaction
     * @param $token string CardToken привязанной карты
     * @param $store_id int Уникальный идентификатор магазина на стороне клиента
     * @return array
     */
    public static function prepareTransaction($trans_id, $token, $store_id)
    {
        // URL для запроса
        $url = 'https://partner.atmos.uz/merchant/pay/pre-apply';

        // Формирование данных для запроса
        $data = [
            'card_token' => $token,
            'store_id' => $store_id,
            'transaction_id' => $trans_id
        ];

        // Отправка запроса и получение ответа
        $res = self::sendAtmos($url, $data);

        // Проверка кода результата
        if ($res->data['result']['code'] == 'OK') {
            // Если код результата успешный, возвращается соответствующий код
            return [
                'code' => $res->data['result']['code'],
            ];
        } else {
            // В противном случае возвращается описание ошибки
            return [
                'code' => $res->data['result']['code'],
                'description' => $res->data['result']['description']
            ];
        }
    }

    /**
     * Метод для начала подтверждения транзакции и списывание средства с карты на терминал мерчанта.
     * @param $trans_id int номер созданной прежде транзакции
     * @return array
     */
    public static function startConfirmation($trans_id)
    {
        // URL для запроса
        $url = 'https://partner.atmos.uz/merchant/pay/apply-ofd';

        // Формирование данных для запроса
        $data = [
            'otp' => 111111,
            'store_id' => \Yii::$app->params['store_id'],
            'transaction_id' => $trans_id,
        ];

        // Отправка запроса и получение ответа
        $res = self::sendAtmos($url, $data);

        // Проверка кода результата
        if ($res->data['result']['code'] == 'OK') {
            // Если код результата успешный, возвращаются соответствующие данные
            return [
                'code' => $res->data['result']['code'],
                'info' => $res->data['store_transaction'],
            ];
        } else {
            // В противном случае возвращается описание ошибки
            return [
                'code' => $res->data['result']['code'],
                'description' => $res->data['result']['description']
            ];
        }
    }

    /**
     * (НЕРАБОЧИЙ) метод для создания Реккурентных платежей
     * @param $model object
     * @return array|false
     */
    public static function startSchedule($model)
    {

        $url = 'https://partner.atmos.uz/pay-scheduler/create';
        $begin = date('Y-m-d', strtotime($model->doc_date_start));
        $end = date('Y-m-d', strtotime($model->doc_date_end));
        $card = ClientCards::findOne(['client_id' => $model->client_id, 'status' => 0]);
        if (!$card) {
            return false;
        }
        $plan = CreditPlan::find()->where(['credit_id' => $model->id])->andWhere(['<=', 'pay_status', 0])->orderBy(['created' => SORT_ASC])->one();
        $acc = "$model->id/$plan->id";
        $data = [
            'payment' => [
                'date_start' => $begin,
                'date_finish' => $end,
                'login' => $card->client_phone,
                'pay_day' => date('d', strtotime($model->pay_day)),
                'pay_time' => "12:00",
                'repeat_interval' => 30,
                'repeat_times' => 2,
                'account' => $acc,
                'ext_id' => $model->client_id,
                'repeat_low_balance' => true,
                'amount' => ($plan->pay_summa * 100),
                'cards' => "[$card->atmos_card_id]",
                'store_id' => \Yii::$app->params['store_id'],
            ]
        ];
        $res = self::sendAtmos($url, $data);
        return [
            'scheduler_id' => $res->data['scheduler_id']
        ];
    }

    /**
     * Создание транзакции на стороне платформы для дальнейшей работы
     * @param $credit_id int ID Заключённого договора
     * @return string json_encode
     */
    public static function startPinflTransaction($credit_id)
    {

        $url = 'https://apigw.atmos.uz/ppa/transaction/create';
        $model = Credit::findOne(['id' => $credit_id]);
        $client = Client::findOne(['id' => $model->client_id]);
        $plan = CreditPlan::find()->where(['credit_id' => $model->id, 'pay_status' => 0])->orderBy(['created' => SORT_ASC])->one();
        if (!$model or !$client or !$plan) return false;

        // Разделяем строку ФИО на три части
        $name_parts = explode(' ', $client->fullname);
        //Фамилия    //Имя        //Отчество
        [$last_name, $first_name, $middle_name] = $name_parts;

        // Разделяем серию и номер паспорта
        $serial = preg_replace('/[^A-Z]/', '', $client->passport_numb);
        $numbers = preg_replace('/[^0-9]/', '', $client->passport_numb);

        $data = [
            'ext_id' => "$plan->id" . time(),
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'pinfl' => $client->passport_pinfl,
            'passport_series' => $serial,
            'passport_number' => $numbers,
            'contract_number' => $model->id,
            'amount' => ($plan->pay_summa * 100),
        ];
        $res = self::sendAtmos($url, $data, 'apigw.atmos.uz');
        $res_data = $res->data['payload'];
        echo "<pre>";
        print_r($res->data);
        die();
        if ($res->data['code'] != 200) return false;

        $arr = json_encode($res_data);
        return $arr;
    }

    /**
     * Подтверждение списания средств по уникальным идентификаторам транзакции
     * @param $ext_id int Уникальный идентификатор транзакции
     * @param $transaction_id string Уникальный токен полученный от Atmos
     * @return mixed
     */
    public static function confirmPinflTransaction($ext_id, $transaction_id)
    {
        $url = 'https://apigw.atmos.uz/ppa/transaction/confirm';
        $data = [
            'ext_id' => $ext_id,
            'transaction_id' => $transaction_id,
        ];
        $res = self::sendAtmos($url, $data, 'apigw.atmos.uz');
        $res_data = json_encode($res->data['payload']);
        echo "<pre>";
        print_r($res->data);
        die();
        if ($res->data['code'] != 200) return false;

        return json_encode($res_data);
    }

    /**
     * Находит транзакцию по указанным реквизитам, и возвращает по ней детализацию
     * @param $plan_id int Уникальный идентификартор плана
     * @return array
     */
    public static function checkPinflTransaction($plan_id)
    {
        $url = 'https://apigw.atmos.uz/ppa/transaction/check';
        $plan = CreditPlan::findOne(['id' => $plan_id]);
        $serial = preg_replace('/[^A-Z]/', '', $plan->client->passport_numb);
        $numbers = preg_replace('/[^0-9]/', '', $plan->client->passport_numb);
        $data = [
            'ext_id' => "1842521717239033",
            'pinfl' => $plan->client->passport_pinfl,
            'passport_series' => $serial,
            'passport_number' => $numbers,
            'contract_number' => $plan->credit_id,
            'amount' => ($plan->pay_summa * 100),
        ];
        $res = self::sendAtmos($url, $data, 'apigw.atmos.uz');
        echo "<pre>";
        print_r($res->data);
        die();
        return $res->data;

    }

    /**
     * Метод отменяет транзакцию в ATMOS.PAY по паре ее уникальных идентификаторов, до того, как она будет подтверждена
     * @param $plan_id int Уникальный идентификартор плана
     * @return void
     */
    public static function cancelPinflTransaction($plan_id)
    {
        $plan = CreditPlan::findOne(['id' => $plan_id]);
        $data = [
            'ext_id' => "$plan->credit_id$plan->id",
            'transaction_id' => 'f0e223dd-1f62-33c5-a835-631fc57bb861'
        ];
        $url = 'https://apigw.atmos.uz/ppa/transaction/cancel';
        $res = self::sendAtmos($url, $data, 'apigw.atmos.uz');
        echo "<pre>";
        print_r($res->data);
        die();
    }

    /**
     * Метод для скоринга банковских карт клиента
     * @param $token string card_token полученный от Atmos
     * @return mixed
     */
    public static function checkCardScoring($token)
    {
        $url = "https://partner.atmos.uz/scoring/score/by-token/$token";
        $bearer = self::getToken('NZTlCYdVw8ZRBbDalgaUg8OtpFEa:kFGYAX8BkS3xVpxBDYrsWY3LuWYa');
        $httpClient = self::getClient();;
        $req = $httpClient->createRequest()
            ->setUrl($url)
            ->addHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])
            ->setMethod('GET')
            ->send();
        return $req->data;
    }

    /**
     * Защищенный метод для отправки запросов к API Atmos
     * @param $url string Ссылка HTTP REQUEST
     * @param $data array Массив с параметрами
     * @param $host string Host клиента
     * @return mixed
     */
    protected static function sendAtmos($url, $data, $host = 'partner.atmos.uz')
    {
        // Получение авторизационного токена
        $token = self::getToken();
        if ($host == 'apigw.atmos.uz') {
            $token = self::getPinflToken();
        }

        // Отправка запроса с токеном
        $response = self::getClient()->post($url, json_encode($data), [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token",
            'Host' => $host,
        ])->send();

        // Возвращение ответа
        return $response;
    }

    /**
     * Получение HTTP-клиента для отправки запросов
     * @return \yii\httpclient\Client
     */
    protected static function getClient()
    {
        return new \yii\httpclient\Client();
    }

    /**
     * Получение авторизационного токена для доступа к API Atmos
     * @param $a string Уникальный токен идентификации
     * @return string
     */
    public static function getToken($a = 'fNN3PWOVm8YWOKccgOQKvmd5G_wa:bTI6WpwgBB7f8akDTs8yyLtNDfMa')
    {
        // Формирование данных для запроса токена
        $data = ['grant_type' => 'client_credentials'];

        // URL для запроса токена
        $url = 'https://partner.atmos.uz/token';

        // Формирование заголовка авторизации
        $base64 = base64_encode($a); //fNN3PWOVm8YWOKccgOQKvmd5G_wa:bTI6WpwgBB7f8akDTs8yyLtNDfMa

        // Отправка запроса и получение ответа
        $response = self::getClient()->post($url, $data, [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => "Basic $base64",
            'Host' => 'partner.atmos.uz',
            'Content-Length' => 29
        ])->send();

        // Возвращение авторизационного токена
        if (key_exists('access_token', $response->data)) {
            return $response->data['access_token'];
        }
        echo "<pre>";
        return $response;
    }

    /**
     * Получение авторизационного токена для доступа к API Atmos
     * @return string
     */
    protected static function getPinflToken()
    {
        $data = ['grant_type' => 'client_credentials',];
        // URL для запроса токена
        $url = 'https://apigw.atmos.uz/token';

        // Формирование заголовка авторизации
        $base64 = base64_encode(\Yii::$app->params['atmos_pinfl_token']);

        // Отправка запроса и получение ответа
        $response = self::getClient()->post($url, $data, [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => "Basic $base64",

        ])->send();

        // Возвращение авторизационного токена
        if (key_exists('access_token', $response->data)) {
            return $response->data['access_token'];
        } else {
            print_r($response);
            die();
        }

    }
}
