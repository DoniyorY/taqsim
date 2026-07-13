<?php
namespace frontend\controllers;
use yii\web\Controller;
use Yii;

class AnalyticsController extends Controller
{

    public function actionIndex()
    {
        $today = mktime('0', '0', '0');
        $now = time();
        $before = strtotime('- 1 year', $now);
        if ($get = \Yii::$app->request->get('Search')) {
            $before = strtotime($get['begin_date']);
            $now = strtotime($get['end_date']);
        }
        $company_count = \common\models\Company::find()->count();
        $credit_count = \common\models\Credit::find()->where(['credit_status' => 2])->count();
        $credit_new_count = \common\models\Credit::find()
            ->where(['between', 'created', strtotime('monday this week'), strtotime('sunday this week')])
            ->count();
        $unpayed_plans = \common\models\CreditPlan::find()
            ->joinWith('credit')
            ->where(['credit_plan.pay_status' => 0])
            ->andWhere(['<', 'credit_plan.created', time()])
            ->andWhere(['!=', 'credit_status', -2])
            ->count();
        $client_count = \common\models\Client::find()->where(['client_type' => 0])->count();
        $plan_today_sum = \common\models\CreditPlan::find()->where(['between', 'created', $today, $today + 86399])->sum('pay_summa');
        $payment_today_sum = \common\models\Payments::find()->where(['between', 'created', $today, $today + 86399])->sum('amount');
        return $this->render('index', [
            'company_count' => $company_count,
            'credit_count' => $credit_count,
            'unpayed_plans' => $unpayed_plans,
            'client_count' => $client_count,
            'plan_today_sum' => $plan_today_sum,
            'payment_today_sum' => $payment_today_sum,
            'before' => $before,
            'now' => $now
        ]);

    }


    public function actionCompanyChart($begin, $end)
    {
        $query = (new \yii\db\Query())->select([
            'com.name',
            'COUNT(c.id) as credit_count',
        ])
            ->from('credit c')
            ->leftJoin('company as com', 'com.id = c.company_id')
            ->andWhere(['between', 'c.created', $begin, $end])
            ->groupBy('com.id')
            ->all();
        return $this->asJson($query);
    }

    public function actionPaymentPieChart($begin, $end)
    {
        $query = (new \yii\db\Query())
            ->select([
                'method_id',
                'SUM(amount) AS total_amount'
            ])
            ->from('payments')
            ->andWhere(['between', 'created', $begin, $end])
            ->groupBy('method_id')
            ->all();
        $result = [];
        foreach ($query as $row) {

            if ((int)$row['method_id'] === 0) {
                $method = 'Наличные';
            } elseif ((int)$row['method_id'] === 1) {
                $method = 'Перечисление';
            } elseif ((int)$row['method_id'] === 2) {
                $method = 'ATMOS';
            } elseif ((int)$row['method_id'] === 3) {
                $method = 'ALGENIX';
            } else {
                continue;
            }
            $result[] = [
                'method' => $method,
                'total' => (int)$row['total_amount']
            ];
        }
        return $this->asJson($result);
    }

    public function actionPaymentLineChart($begin, $end)
    {
        $query = (new \yii\db\Query())
            ->select([
                "DATE(FROM_UNIXTIME(created)) AS pay_date",
                "SUM(amount) AS total_amount"
            ])
            ->from('payments')
            ->andWhere(['!=', 'amount', 0])
            ->andWhere(['between', 'created', $begin, $end])
            ->groupBy('pay_date')
            ->orderBy('pay_date ASC')
            ->all();
        return $this->asJson($query);
    }

    public function actionPaymentCompanyBarChart($begin, $end)
    {
        // $today = time();
        // $before = strtotime('-1 year', $today); // можно менять диапазон

        $query = (new \yii\db\Query())
            ->select([
                'com.name AS company_name',
                'SUM(p.amount) AS total_amount'
            ])
            ->from('payments p')
            ->leftJoin('company com', 'com.id = p.company_id')
            ->where(['between', 'p.created', $begin, $end])
            ->andWhere(['!=', 'p.amount', 0])
            ->groupBy('p.company_id')
            ->orderBy('total_amount DESC')
            ->all();

        $result = array_map(function ($row) {
            return [
                'company' => $row['company_name'] ?: 'Без филиала',
                'total' => (float)$row['total_amount']
            ];
        }, $query);

        return $this->asJson($result);
    }

    public function actionClientAgeBar($begin, $end)
    {
        $query = (new \yii\db\Query())
            ->select([
                "CASE
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 26 AND 30 THEN '26-30'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 31 AND 40 THEN '31-40'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 41 AND 50 THEN '41-50'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 51 AND 60 THEN '51-60'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 61 AND 70 THEN '61-70'
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 71 AND 80 THEN '71-80'
                ELSE '80+'
            END AS age_group",
                "COUNT(*) AS total"
            ])
            ->from('client')
            ->where(['IS NOT', 'birthday', null])
            ->andWhere(['client_type' => 0])
            ->andWhere(['between', 'created', $begin, $end])
            ->groupBy('age_group')
            ->orderBy([
                "FIELD(age_group, '18-25','26-30','31-40','41-50','51-60','61-70','71-80', '80+')" => SORT_ASC
            ])
            ->all();

        return $this->asJson($query);
    }

    public function actionDelaysByAge($begin, $end)
    {
        $sql = "
        SELECT 
            cp.id,
            cp.created AS due_date,
            cp.pay_status,
            AVG(p.created) AS avg_pay_date,
            c.birthday
        FROM credit_plan cp
        INNER JOIN credit cr ON cp.credit_id = cr.id
        INNER JOIN client c ON cr.client_id = c.id
        LEFT JOIN payments p ON cp.id = p.credit_plan_id
        WHERE cr.credit_status = 2
          AND c.client_type = 0
          AND cr.created BETWEEN :begin AND :end
        GROUP BY cp.id, cp.created, cp.pay_status, c.birthday
    ";

        $rows = Yii::$app->db->createCommand($sql)
            ->bindValue(':begin', $begin)
            ->bindValue(':end', $end)
            ->queryAll();

        $ageGroups = [
            '18-25' => [18, 25],
            '26-30' => [26, 30],
            '31-40' => [31, 40],
            '41-50' => [41, 50],
            '51-60' => [51, 60],
            '61-70' => [61, 70],
            '71-80' => [71, 80],
            '80+'   => [81, 200]
        ];

        $labels = array_keys($ageGroups);

        $paidCount = array_fill(0, count($labels), 0);
        $lateCount = array_fill(0, count($labels), 0);
        $delaySum  = array_fill(0, count($labels), 0);

        $now = new \DateTime();

        foreach ($rows as $row) {

            if (empty($row['birthday'])) {
                continue;
            }

            $dob = new \DateTime($row['birthday']);
            $age = $dob->diff($now)->y;

            $groupIndex = null;
            foreach ($ageGroups as $label => $range) {
                if ($age >= $range[0] && $age <= $range[1]) {
                    $groupIndex = array_search($label, $labels);
                    break;
                }
            }

            if ($groupIndex === null) {
                continue;
            }

            // считаем только оплаченные
            if ((int)$row['pay_status'] !== 1 || !$row['avg_pay_date']) {
                continue;
            }

            $dueDate = (int)$row['due_date'];
            $avgPay  = (int)$row['avg_pay_date'];

            $diff = floor(($avgPay - $dueDate) / 86400);
            $delayDays = $diff > 0 ? $diff : 0;

            $paidCount[$groupIndex]++;
            $delaySum[$groupIndex] += $delayDays;

            if ($delayDays > 0) {
                $lateCount[$groupIndex]++;
            }
        }

        $averageDays = [];
        $latePercent = [];

        foreach ($labels as $i => $label) {

            // средняя просрочка
            $avg = $paidCount[$i] > 0
                ? round($delaySum[$i] / $paidCount[$i], 1)
                : 0;

            $averageDays[] = $avg;

            // % оплат с просрочкой
            $percent = $paidCount[$i] > 0
                ? round(($lateCount[$i] / $paidCount[$i]) * 100, 1)
                : 0;

            $latePercent[] = $percent;
        }

        return $this->asJson([
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Средняя просрочка (дней)',
                    'type' => 'column',
                    'data' => $averageDays
                ],
                [
                    'name' => 'Оплачено с просрочкой (%)',
                    'type' => 'line',
                    'data' => $latePercent
                ]
            ]
        ]);
    }
}