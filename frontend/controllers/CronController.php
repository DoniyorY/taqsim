<?php

namespace frontend\controllers;

use common\models\CreditPlan;
use common\models\LawyerData;
use common\models\Settings;
use common\models\Credit;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;


/**
 * ClientController implements the CRUD actions for Client model.
 */
class CronController extends Controller
{

    public function actionTransaction()
    {
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86399;

        $models = CreditPlan::find()->joinWith('credit')
            ->where(['pay_status' => 0])
            ->andWhere(['credit.credit_status' => 2])
            ->andWhere(['between', 'created', $dayStart, $dayEnd])
            ->all();

        foreach ($models as $model) {
            if ($model->credit->content === 'тест') {
                continue;
            }

            $check_date = date('d.m.Y', $model->created);
            if (date('d.m.Y') !== $check_date || $model->pay_status !== 0) {
                continue;
            }
            $cards = \common\models\ClientCards::find()
                ->where(['client_id' => $model->client_id, 'status' => 0])
                ->andWhere(['IS NOT', 'token', null])
                ->all();

            if (empty($cards)) {
                continue;
            }

            $transactionSuccessful = false;

            foreach ($cards as $card) {
                $payment = \common\models\Payments::find()->where(['credit_id' => $model->credit_id, 'credit_plan_id' => $model->id])->sum('amount');
                $amount = (($model->pay_summa - $payment) * 100);
                if ($amount <= 0) continue;
                // Starting Transaction
                $data = [
                    'amount' => $amount,
                    'account' => "$model->credit_id/$model->id",
                    'store_id' => \Yii::$app->params['store_id'],
                    'lang' => 'ru',
                ];
                $atmos_invoice = AtmosController::startTransaction($data);

                if (!is_array($atmos_invoice)) {
                    continue;
                }

                $atmos_prepare = AtmosController::prepareTransaction($atmos_invoice['trans_id'], $card->token, \Yii::$app->params['store_id']);
                if ($atmos_prepare['code'] !== 'OK') {
                    continue;
                }

                $atmos_confirm = AtmosController::startConfirmation($atmos_invoice['trans_id']);
                if ($atmos_confirm['code'] === 'OK') {
                    [$credit_id, $plan_id] = explode('/', $atmos_confirm['info']['account']);

                    $credit = \common\models\Credit::findOne(['id' => $credit_id]);
                    if (!$credit) {
                        continue;
                    }

                    $payment = new \common\models\Payments();
                    $payment->created = time();
                    $payment->user_id = 10;
                    $payment->company_id = $credit->company_id;
                    $payment->credit_id = $credit_id;
                    $payment->payment_type = 0;
                    $payment->credit_type_id = $credit->credit_type_id;
                    $payment->credit_plan_id = $plan_id;
                    $payment->amount = $atmos_confirm['info']['amount'] / 100;
                    $payment->method_id = 2;
                    $payment->pay_type = 1;
                    $payment->content = "Договор № $credit->id. Плановая оплата через банковскую карту: " . \Yii::$app->formatter->asDecimal($payment->amount, 0);
                    $payment->save(false);

                    $plan = \common\models\CreditPlan::findOne($plan_id);
                    if ($plan) {
                        $plan->pay_status = 1;
                        $plan->update(false);
                    }

                    $transactionSuccessful = true;
                    break; // Если транзакция успешна, выходим из цикла карт
                }
            }

            if (!$transactionSuccessful) {
                continue;
            }
        }
    }


    public function actionPinflTransaction()
    {
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86399;

        $models = CreditPlan::find()->where(['id' => 184252])
            //->where(['pay_status' => 0])
            //->andWhere(['between', 'created', $dayStart, $dayEnd])
            ->all();
        foreach ($models as $model) {
            /* if ($model->credit->content === 'тест') {
                 continue;
             }*/

            /*$check_date = date('d.m.Y', $model->created);
            if (date('d.m.Y') !== $check_date || $model->pay_status !== 0) {
                continue;
            }*/

            $card = \common\models\ClientCards::find()
                ->where(['client_id' => $model->client_id, 'status' => 0])
                ->andWhere(['is not', 'token', null])
                ->one();

            if (!$card) {
                continue;
            }

            $startAtmos = AtmosController::startPinflTransaction($model->credit_id);
            if (!$startAtmos) {
                continue;
            }
            $startAtmos = json_decode($startAtmos);

            $ext = $startAtmos->ext_id ?? 0;
            $trans = $startAtmos->transaction_id ?? 0;
            echo "<pre>";
            print_r($startAtmos);
            echo "<hr>";
            print_r($ext);
            echo "<hr>";
            print_r($trans);
            die();
            $confirmAtmos = AtmosController::confirmPinflTransaction($ext, $trans);
            if (!$confirmAtmos) {
                continue;
            }
            $confirmAtmos = json_decode($confirmAtmos);
            echo "<hr>";
            print_r($confirmAtmos);
            die();
            $amount = $confirmAtmos->amount / 100;
            $credit = \common\models\Credit::findOne(['id' => $model->credit_id]);
            if (!$credit) {
                continue;
            }

            $payment = new \common\models\Payments();
            $payment->created = time();
            $payment->user_id = 10;
            $payment->company_id = $credit->company_id;
            $payment->credit_id = $model->credit_id;
            $payment->payment_type = 0;
            $payment->credit_type_id = $credit->credit_type_id;
            $payment->credit_plan_id = $model->id;
            $payment->amount = $amount;
            $payment->method_id = 2;
            $payment->pay_type = 1;
            $payment->content = "Договор № $credit->id. Плановая оплата через банковскую карту: " . \Yii::$app->formatter->asDecimal($payment->amount, 0);
            $payment->save(false);

            $model->pay_status = 1;
            $model->update(false);
        }
    }

    public function actionCheckUnpayed()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $now = time();
        $twentyDaysAgo = $now - 20 * 24 * 60 * 60;

        // 1) Берём ID кредитов, у которых есть НЕОПЛАЧЕННЫЕ планы старше 20 дней
        $creditIds = CreditPlan::find()
            ->alias('cp')
            ->joinWith('credit c')
            ->select(['c.id'])              // только ID кредита
            ->filterWhere(['cp.pay_status' => [0,4,5,6]]) // план не оплачен, у юриста: новые, у юриста: на рассмотрении, у юриста: в суде
            ->andFilterWhere(['<', 'cp.created', $twentyDaysAgo]) // план создан более 20 дней назад
            ->andFilterWhere(['c.credit_status'=>2])
            ->andFilterWhere(['c.algenix_autopay_locked' => 0])
            ->andFilterWhere(['not like', 'c.content', 'Тест'])
            ->groupBy('c.id')
            ->column(); // сразу массив ID кредитов

        // 2) Проставляем algenix_autopay = 1 только тем, кто:
        //    - в списке просроченных
        //    - не залочен
        $updatedTo1 = 0;
        if (!empty($creditIds)) {
            $updatedTo1 = Credit::updateAll(
                ['algenix_autopay' => 1],
                [
                    'and',
                    ['id' => $creditIds],
                    ['algenix_autopay_locked' => 0],
                ]
            );
        }

        // 3) Сбрасываем algenix_autopay = 0 у тех, у кого:
        //    - сейчас algenix_autopay = 1
        //    - не залочены
        //    - НЕ попали в список просроченных (значит, не подходят под критерий «20+ дней не оплачено»)
        $updatedTo0 = Credit::updateAll(
            ['algenix_autopay' => 0],
            [
                'and',
                ['algenix_autopay' => 1],
                ['algenix_autopay_locked' => 0],                   // <── тоже важно
                ['not in', 'id', !empty($creditIds) ? $creditIds : [0]],
            ]
        );


        return [
            'ok' => true,
            'overdue_credit_ids' => $creditIds,
            'updated_to_1' => $updatedTo1,
            'updated_to_0' => $updatedTo0,
        ];
    }

    public function actionSendWarningSms()
    {

        $plans = CreditPlan::find()
            ->where(['pay_status'=>0])
            ->andWhere(['between','created',time(), strtotime("+1 day")])
        ->all();
        foreach ($plans as $plan){
            $total = CreditPlan::find()
                ->where(['credit_id'=>$plan->credit_id])
                ->andWhere(['pay_status'=>0])
                ->andWhere(['<','created',strtotime("+1 day")])
                ->sum('pay_summa');

            $client= \common\models\Client::findOne($plan->credit->client_id);
            $pay_amount = \Yii::$app->formatter->asDecimal($total??0,0);
            $datetime=date('d.m.Y',$plan->created);
            $message="Аssalomu alaykum. Hurmatli mijoz,
Sizning Shartnoma № $plan->credit_id raqamingiz bo'yicha $pay_amount so'm miqdorida $datetime sanasiga to'lovingiz mavjud.
Iltimos qarzdorlikni o'z vaqtida amalga oshirib berishingizni so'raymiz. \r\n
Hurmat bilan,\r\n
LUX Gilam savdo markazi.";
            \Yii::$app->playmobile->sendSms($client->phone,$message);
            $log = new \common\models\SmsLog([
                'recipient'=>$client->phone,
                'message_id'=>time(),
                'originator'=>3700,
                'text'=>$message,
                'status'=>0,
            ]);
            $log->save(false);

        }
        return $this->asJson($plans);
    }
}
