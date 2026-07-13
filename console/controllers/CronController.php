<?php

namespace console\controllers;

use common\models\CreditPlan;
use common\models\LawyerData;
use common\models\Payments;
use common\models\Settings;
use yii\console\Controller;
use Yii;

class CronController extends Controller
{
    public function actionSendWarningSms()
    {
        Yii::info('CRON send-warning-sms started at ' . date('Y-m-d H:i:s'), 'cron.sendWarningSms');

        $start = strtotime('tomorrow 00:00:00');
        $end = strtotime('tomorrow 23:59:59');

        $plans = CreditPlan::find()
            ->joinWith('credit')
            ->where(['pay_status' => 0])
            ->andWhere(['credit.credit_status' => 2])
            ->andWhere(['between', 'created', $start, $end])
            ->all();

        Yii::info('Found plans: ' . count($plans), 'cron.sendWarningSms');

        $k = 0;

        foreach ($plans as $plan) {

            Yii::info("Processing plan ID={$plan->id}, credit_id={$plan->credit_id}", 'cron.sendWarningSms');

            $total = CreditPlan::find()
                ->where(['credit_id' => $plan->credit_id])
                ->andWhere(['pay_status' => 0])
                ->andWhere(['<', 'created', strtotime("+1 day")])
                ->sum('pay_summa');

            $client = \common\models\Client::findOne($plan->credit->client_id);

            if (!$client) {
                Yii::error("Client not found for credit_id={$plan->credit_id}", 'cron.sendWarningSms');
                continue;
            }

            $pay_amount = Yii::$app->formatter->asDecimal($total ?? 0, 0);
            $datetime = date('d.m.Y', $plan->created);

            $message = "Assalomu alaykum. Hurmatli mijoz, Sizning Shartnoma № {$plan->credit_id} raqamingiz bo'yicha {$pay_amount} so'm miqdorida {$datetime} sanasiga to'lovingiz mavjud. Iltimos qarzdorlikni o'z vaqtida amalga oshirib berishingizni so'raymiz. Hurmat bilan, LUX Gilam savdo markazi.";

            try {
                $res = Yii::$app->playmobile->sendSms($client->phone, $message);

                Yii::info("SMS SENT to {$client->phone} (plan {$plan->id})", 'cron.sendWarningSms');

            } catch (\Exception $e) {
                Yii::error("SMS FAILED for {$client->phone}: " . $e->getMessage(), 'cron.sendWarningSms');
                continue;
            }

            $log = new \common\models\Smslog([
                'recipient' => $client->phone,
                'message_id' => time(),
                'originator' => 3700,
                'text' => $res,
                'status' => 0,
            ]);

            if (!$log->save(false)) {
                Yii::error("FAILED save SmsLog for {$client->phone}", 'cron.sendWarningSms');
            }

            $k++;
        }

        Yii::info("CRON send-warning-sms finished. Sent count: {$k}", 'cron.sendWarningSms');

        return 0;
    }

    public function actionCheckPlans()
    {
        \Yii::info('CRON check-plans started at ' . date('Y-m-d H:i:s'), 'cron.checkPlans');
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86400;
        $db = Yii::$app->db->beginTransaction();
        try {
            $dataProvider = CreditPlan::find()
                ->joinWith('credit')
                ->andWhere(['credit.credit_status' => 2])
                ->where(['pay_status' => 0])
                ->andWhere(['<', 'credit_plan.created', $dayStart])
                ->all();
            \Yii::info('Found plans: ' . count($dataProvider), 'cron.checkPlans');
            $settings = Settings::find()->one();
            foreach ($dataProvider as $item) {
                \Yii::info("Checking plan ID={$item->id}, credit_id={$item->credit_id}", 'cron.checkPlans');
                $days_limit = $item->created + 86400 * $settings->value;
                $payment = Payments::findAll(['credit_plan_id' => $item->id]);

                if (!empty($payment)) {
                    $pay_amount = 0;
                    foreach ($payment as $v) {
                        $pay_amount += $v->amount;
                    }
                    $ost = $item->pay_summa - $pay_amount;
                    if ($item->pay_summa <= $pay_amount) {
                        \Yii::info("SKIP plan {$item->id} — payments exist", 'cron.checkPlans');
                        continue;
                    } elseif ($ost <= 5000) {
                        \Yii::info("SKIP plan {$item->id} — payments exist", 'cron.checkPlans');
                        continue;
                    }

                }
                if (time() > $days_limit and $item->pay_status === 0) {
                    \Yii::info("SEND TO LAWYER plan {$item->id}", 'cron.checkPlans');
                    $item->yurist_goday = time();
                    $item->pay_status = 4;
                    $item->is_sent_sms = 1;
                    if (!$item->update(false)) {
                        Yii::error("FAILED update CreditPlan ID={$item->id}", 'cron.checkPlans');
                        continue;
                    }

                    //$item->credit->credit_status=4;
                    //$item->credit->update();
                }
                $lawyer = new LawyerData();
                $lawyer->user_new = 1;
                $lawyer->user_consideration = null;
                $lawyer->user_finished = null;
                $lawyer->user_judgement = null;
                $lawyer->updated_new = time();
                $lawyer->updated_consideration = null;
                $lawyer->updated_finished = null;
                $lawyer->updated_judgement = null;
                $lawyer->status = 0;
                $lawyer->credit_id = $item->credit_id;
                if (!$lawyer->save(false)) {
                    \Yii::error("FAILED save LawyerData credit_id={$item->credit_id}", 'cron.checkPlans');
                }
            }
            \Yii::info('CRON check-plans finished at ' . date('d.m.Y H:i:s'), 'cron.checkPlans');
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            \Yii::error($e->getMessage(), 'cron.checkPlans');
        }
    }
}