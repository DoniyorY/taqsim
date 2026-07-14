<?php

namespace frontend\controllers;

use Faker\Provider\Payment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\search\CreditSearch;
use common\models\search\PaymentsSearch;
use common\models\Payments;
use common\models\CreditPlan;
use common\models\CompanyPlanLimit;


class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'credit',
                            'index',
                            'statistic',
                            'statistic-count',
                            'report',
                            'company',
                            'company-index',
                            'credit-index',
                            'lawyer',
                            'dept',
                            'company-limit-statistic',
                            'update-autopay'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCompanyIndex()
    {
        if (Yii::$app->request->get()) {
            $start = strtotime(Yii::$app->request->get('start'));
            $end = strtotime(Yii::$app->request->get('end')) + 86399;
            $company = $_GET['company'];
            if (empty($start) or empty($end)) {
                $start = strtotime(date('Y-m-01'));
                $end = strtotime(date('Y-m-t'));
            }
            $credits = \common\models\Credit::find()
                ->andFilterWhere(['<>', 'credit_status', -2])
                ->andFilterWhere(['company_id' => $company])
                ->andFilterWhere(['between', 'created', $start, $end])
                ->orderby('created DESC')
                ->all();
        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
            $credits = \common\models\Credit::find()
                ->where(['>=', 'credit_status', 0])
                ->andWhere(['rejected' => 0])
                //->andFilterWhere(['credit_status'=>1])
                ->andWhere(['between', 'created', $start, $end])
                ->orderby('created DESC')
                ->all();
        }
        return $this->render('credit_company_index', [
            'start' => $start,
            'end' => $end,
            'credits' => $credits,
        ]);
    }

    public function actionCompany()
    {
        $request = Yii::$app->request->get('date_begin');
        if (isset($request)) {
            $start = strtotime(Yii::$app->request->get('date_begin'));
            $end = strtotime(Yii::$app->request->get('date_end')) + 86399;

            $user_id = Yii::$app->request->get('user_id');
            $company_id = Yii::$app->request->get('company_id');

            if (!empty($user_id)) {
                $extra_where_user = ' AND c.user_id =' . $user_id;
            } else {
                $extra_where_user = '';
            }
            if (!empty($company_id)) {
                $extra_where_com = ' AND c.company_id =' . $company_id;
            } else {
                $extra_where_com = '';
            }
        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));

            $extra_where_user = '';
            $extra_where_com = '';
        }

        $sql = '
          SELECT
                c.id, cl.fullname, cl.phone, c.doc_date_start, u.username, co.name,
                (c.self_price) as real_summa,
                c.prepaid_summa,
                SUM(p.amount) as psumma
            FROM `credit` c
                     LEFT JOIN `client` cl ON c.client_id=cl.id
                     LEFT JOIN `company` co ON c.company_id=co.id
                     LEFT JOIN `user` u ON c.user_id=u.id
                     LEFT JOIN `payments` p ON c.id=p.credit_id
            WHERE c.credit_status >= 0
            GROUP by c.id
        ';

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $this->render('credit_company', [
            'result' => $result,
            'start' => $start,
            'end' => $end,

        ]);
    }


    public function actionCredit($title = null)
    {

        if ($this->request->isPost) {
            $start = strtotime(Yii::$app->request->post('start'));
            $end = strtotime(Yii::$app->request->post('end')) + 86399;
            $company = $_POST['company'];
            if (empty($start) or empty($end)) {
                $start = strtotime(date('Y-m-01'));
                $end = strtotime(date('Y-m-t'));
            }
            if (!empty($company)) {
                $extra_com = ' AND c.company_id =' . $company;
            } else {
                $extra_com = '';
            }

        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
            $extra_com = '';
        }

        $sql = '
          SELECT c.*, cl.fullname, cl.phone, co.name, u.username, c.self_price as real_summa 
           FROM credit AS c 
                 LEFT JOIN `client` cl ON c.client_id = cl.id
                 LEFT JOIN `company` co ON c.company_id = co.id
                 LEFT JOIN `user` u ON c.user_id = u.id     
                 WHERE (c.credit_status<>-2) AND (c.rejected=0) AND (c.created BETWEEN ' . $start . ' AND ' . $end . ') ' . $extra_com . '
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';

        $payments = '
          SELECT c.id, COALESCE(SUM(p.amount),0) as psumma, c.self_price-COALESCE(SUM(p.amount), 0)-c.prepaid_summa as ost
          FROM credit AS c 
                 JOIN payments AS p ON p.credit_id = c.id   
                 WHERE (c.credit_status<>-2) AND (c.rejected=0) AND (c.created BETWEEN ' . $start . ' AND ' . $end . ') ' . $extra_com . '
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $command2 = $connection->createCommand($payments);
        $result = $command->queryAll();
        $result2 = $command2->queryAll();

        if ($title == 'companyindex') {
            $title = 'Хисобот дуконлар буйича';
        } else {
            $title = 'Таннарх буйича хисобот';
        }
        return $this->render('credit', [
            'start' => $start,
            'end' => $end,
            'credits' => $result,
            'payments' => $result2,
            'title' => $title
        ]);
    }

    public function actionCreditIndex()
    {
        if (Yii::$app->request->get('Search')) {
            $get_request = $_GET['Search'];
            $start = strtotime($get_request['start']);
            $end = strtotime($get_request['end']) + 86399;
            $company = $get_request['company'];
            if (empty($start) or empty($end)) {
                $start = strtotime(date('Y-m-01'));
                $end = strtotime(date('Y-m-t'));
            }
            if (!empty($company)) {
                $extra_com = ' AND c.company_id =' . $company;
            } else {
                $extra_com = '';
            }
            $type_post = $get_request['credit-type'];
            if ($type_post) {
                $type_request = 'AND c.credit_type_id=' . $type_post;
            } else {
                $type_request = '';
            }
        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
            $extra_com = '';
            $type_request = '';
        }
        $sql = '
          SELECT c.*, cl.fullname, cl.phone, co.name, u.username, c.self_price as real_summa, c.percent, c.prepaid_summa, ct.name as type_name
           FROM credit AS c 
                 LEFT JOIN `client` cl ON c.client_id = cl.id
                 LEFT JOIN `company` co ON c.company_id = co.id
                 LEFT JOIN `user` u ON c.user_id = u.id     
                 LEFT JOIN `credit_type` ct ON c.credit_type_id = ct.id     
                 WHERE (c.credit_status<>-2) AND (c.rejected=0) AND (c.created BETWEEN ' . $start . ' AND ' . $end . ') ' . $extra_com . ' ' . $type_request . ' 
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';

        $payments = '
          SELECT c.id, SUM(p.amount) as psumma,
            c.percent, c.doc_total_price-COALESCE(SUM(p.amount), 0) as ost
          FROM credit AS c 
                 JOIN payments AS p ON p.credit_id = c.id   
                 WHERE (c.credit_status<>-2) AND (c.rejected=0) AND (c.created BETWEEN ' . $start . ' AND ' . $end . ') and (p.created BETWEEN ' . $start . ' AND ' . $end . ') ' . $extra_com . ' ' . $type_request . '
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $command2 = $connection->createCommand($payments);
        $result = $command->queryAll();
        $result2 = $command2->queryAll();

        return $this->render('credit_index', [
            'start' => $start,
            'end' => $end,
            'credits' => $result,
            'payments' => $result2,
        ]);
    }

    public function actionIndex()
    {
        $company = \common\models\Company::find()->all();
        $request = Yii::$app->request->get('date_begin');
        if (isset($request)) {
            $start = strtotime(Yii::$app->request->get('date_begin'));
            $end = strtotime(Yii::$app->request->get('date_end')) + 86399;
            //$company_id = Yii::$app->request->get('company_id');
        } else {
            $start = strtotime(date('2020-01-01'));
            $end = strtotime(date('Y-m-t'));
        }
        return $this->render('index_old', [
            'start' => $start,
            'end' => $end,
            'company' => $company,
        ]);
    }

    public function actionStatistic()
    {
        $company = \common\models\Company::find()->all();

        $request = Yii::$app->request->get('date_begin');
        if (isset($request)) {
            $start = strtotime(Yii::$app->request->get('date_begin'));
            $end = strtotime(Yii::$app->request->get('date_end')) + 86399;
        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
        }
        return $this->render('statistic', [
            'start' => $start,
            'end' => $end,
            'company' => $company,
        ]);
    }
    
    public function actionStatisticCount(){
        $request = Yii::$app->request->get('date_begin');
        if (isset($request)) {
            $start = strtotime(Yii::$app->request->get('date_begin'));
            $end = strtotime(Yii::$app->request->get('date_end')) + 86399;
        } else {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
        }

        $planCountSubQuery = $this->getStatisticPlanCountSubQuery();
        $statistic = $this->getStatisticCreditRows($planCountSubQuery, $start, $end);
        $contractStatistic = $this->getStatisticContractRows($planCountSubQuery, $start, $end);
        $paymentStatistic = $this->getStatisticPaymentRows($planCountSubQuery, $start, $end);

        $companies = [];
        $monthCounts = [];
        foreach ($statistic as $row) {
            $companyId = $row['company_id'];
            if (!isset($companies[$companyId])) {
                $companies[$companyId] = [
                    'name' => $row['company_name'],
                    'counts' => [],
                    'total' => 0,
                ];
            }

            if ($row['month_count'] !== null) {
                $monthCount = (int)$row['month_count'];
                $creditCount = (int)$row['credit_count'];
                $companies[$companyId]['counts'][$monthCount] = $creditCount;
                $companies[$companyId]['total'] += $creditCount;
                $monthCounts[$monthCount] = $monthCount;
            }
        }
        $monthCounts = array_values(array_unique($monthCounts));
        sort($monthCounts, SORT_NUMERIC);

        $contractCompanies = [];
        $contractMonthCounts = $monthCounts;
        foreach ($contractStatistic as $row) {
            $companyId = $row['company_id'];
            if (!isset($contractCompanies[$companyId])) {
                $contractCompanies[$companyId] = [
                    'name' => $row['company_name'],
                    'sums' => [],
                    'total' => 0,
                ];
            }

            if ($row['month_count'] !== null) {
                $monthCount = (int)$row['month_count'];
                $contractSum = (int)$row['contract_sum'];
                $contractCompanies[$companyId]['sums'][$monthCount] = $contractSum;
                $contractCompanies[$companyId]['total'] += $contractSum;
                $contractMonthCounts[$monthCount] = $monthCount;
            }
        }
        $contractMonthCounts = array_values(array_unique($contractMonthCounts));
        sort($contractMonthCounts, SORT_NUMERIC);

        $paymentCompanies = [];
        $paymentMonthCounts = $monthCounts;
        foreach ($paymentStatistic as $row) {
            $companyId = $row['company_id'];
            if (!isset($paymentCompanies[$companyId])) {
                $paymentCompanies[$companyId] = [
                    'name' => $row['company_name'],
                    'sums' => [],
                    'total' => 0,
                ];
            }

            if ($row['month_count'] !== null) {
                $monthCount = (int)$row['month_count'];
                $paymentSum = (int)$row['payment_sum'];
                $paymentCompanies[$companyId]['sums'][$monthCount] = $paymentSum;
                $paymentCompanies[$companyId]['total'] += $paymentSum;
                $paymentMonthCounts[$monthCount] = $monthCount;
            }
        }
        $paymentMonthCounts = array_values(array_unique($paymentMonthCounts));
        sort($paymentMonthCounts, SORT_NUMERIC);

        return $this->render('statistic_count', [
            'start' => $start,
            'end' => $end,
            'companies' => $companies,
            'monthCounts' => $monthCounts,
            'contractCompanies' => $contractCompanies,
            'contractMonthCounts' => $contractMonthCounts,
            'paymentCompanies' => $paymentCompanies,
            'paymentMonthCounts' => $paymentMonthCounts,
        ]);
    }

    private function getStatisticPlanCountSubQuery()
    {
        return (new Query())
            ->select([
                'credit_id',
                'month_count' => new Expression('COUNT(*)'),
            ])
            ->from('credit_plan')
            ->groupBy('credit_id');
    }

    private function getStatisticCreditRows(Query $planCountSubQuery, $start, $end)
    {
        return (new Query())
            ->select([
                'company_id' => 'co.id',
                'company_name' => 'co.name',
                'month_count' => 'plans.month_count',
                'credit_count' => new Expression('COUNT(c.id)'),
            ])
            ->from(['co' => 'company'])
            ->leftJoin(['c' => 'credit'], $this->getStatisticCreditJoinCondition($start, $end))
            ->leftJoin(['plans' => $planCountSubQuery], 'plans.credit_id = c.id')
            ->groupBy(['co.id', 'co.name', 'plans.month_count'])
            ->orderBy(['co.name' => SORT_ASC, 'plans.month_count' => SORT_ASC])
            ->all();
    }

    private function getStatisticContractRows(Query $planCountSubQuery, $start, $end)
    {
        return (new Query())
            ->select([
                'company_id' => 'co.id',
                'company_name' => 'co.name',
                'month_count' => 'plans.month_count',
                'contract_sum' => new Expression('COALESCE(SUM(c.doc_total_price), 0)'),
            ])
            ->from(['co' => 'company'])
            ->leftJoin(['c' => 'credit'], $this->getStatisticCreditJoinCondition($start, $end))
            ->leftJoin(['plans' => $planCountSubQuery], 'plans.credit_id = c.id')
            ->groupBy(['co.id', 'co.name', 'plans.month_count'])
            ->orderBy(['co.name' => SORT_ASC, 'plans.month_count' => SORT_ASC])
            ->all();
    }

    private function getStatisticPaymentRows(Query $planCountSubQuery, $start, $end)
    {
        $creditJoinCondition = $this->getStatisticCreditJoinCondition($start, $end);
        $creditJoinCondition[] = ['c.rejected' => 0];

        return (new Query())
            ->select([
                'company_id' => 'co.id',
                'company_name' => 'co.name',
                'month_count' => 'plans.month_count',
                'payment_sum' => new Expression('COALESCE(SUM(p.amount), 0)'),
            ])
            ->from(['co' => 'company'])
            ->leftJoin(['c' => 'credit'], $creditJoinCondition)
            ->leftJoin(['plans' => $planCountSubQuery], 'plans.credit_id = c.id')
            ->leftJoin(['p' => 'payments'], 'p.credit_id = c.id')
            ->groupBy(['co.id', 'co.name', 'plans.month_count'])
            ->orderBy(['co.name' => SORT_ASC, 'plans.month_count' => SORT_ASC])
            ->all();
    }

    private function getStatisticCreditJoinCondition($start, $end)
    {
        return [
            'and',
            'c.company_id = co.id',
            ['not in', 'c.credit_status', [-1, -2, 3, 5]],
            ['between', 'c.created', $start, $end],
        ];
    }



    public function actionCompanyLimitStatistic()
    {
        return $this->render('company_limit_statistic', [
            'contractCompanies' => $this->getCompanyLimitStatistic(CompanyPlanLimit::TYPE_CONTRACTS),
            'paymentCompanies' => $this->getCompanyLimitStatistic(CompanyPlanLimit::TYPE_PAYMENTS),
        ]);
    }

    private function getCompanyLimitStatistic($type)
    {
        $rows = $type == CompanyPlanLimit::TYPE_CONTRACTS
            ? $this->getCompanyCreditLimitStatistic()
            : $this->getCompanyPaymentLimitStatistic();
        $companies = [];

        foreach ($rows as $row) {
            $companyId = $row['company_id'];
            if (!isset($companies[$companyId])) {
                $companies[$companyId] = [
                    'company_id' => $companyId,
                    'company_name' => $row['company_name'],
                    'limit' => (int)$row['limit'],
                    'total' => 0,
                    'percent' => null,
                    'salary_total' => 0,
                    'rows' => [],
                ];
            }

            $summa = (int)$row['summa'];
            $companies[$companyId]['total'] += $summa;
            $companies[$companyId]['rows'][] = [
                'credit_type_id' => $row['credit_type_id'],
                'credit_type_name' => $row['credit_type_name'] ?: 'Без типа',
                'summa' => $summa,
            ];
        }

        foreach ($companies as &$company) {
            $company['percent'] = $company['limit'] > 0 ? ($company['total'] / $company['limit']) * 100 : null;
            foreach ($company['rows'] as &$row) {
                $row['salary_percent'] = $this->getCompanyLimitSalaryPercent(
                    $type,
                    $company['company_name'],
                    $row['credit_type_name'],
                    $company['percent']
                );
                $row['salary'] = $row['summa'] * ($row['salary_percent'] / 100);
                $company['salary_total'] += $row['salary'];
            }
            unset($row);
        }
        unset($company);

        usort($companies, function ($left, $right) {
            return strcmp($left['company_name'], $right['company_name']);
        });

        return $companies;
    }


    private function getCompanyLimitSalaryPercent($type, $companyName, $creditTypeName, $companyPercent)
    {
        $percentParams = Yii::$app->params['companyLimitStatisticPercents'][$type] ?? [];
        $defaultPercent = $percentParams['defaultPercent'] ?? 2;
        $creditCategory = $this->getCompanyLimitCreditCategory($creditTypeName);
        $companyName = strtolower($companyName);

        foreach ($percentParams['specialCompanies'] ?? [] as $needle => $percents) {
            if (strpos($companyName, strtolower($needle)) !== false) {
                return $percents[$creditCategory] ?? $percents['default'] ?? $defaultPercent;
            }
        }

        if ($companyPercent !== null) {
            foreach ($percentParams['ranges'] ?? [] as $range) {
                $min = $range['min'] ?? null;
                $max = $range['max'] ?? null;
                if (($min === null || $companyPercent >= $min) && ($max === null || $companyPercent <= $max)) {
                    return $range[$creditCategory] ?? $defaultPercent;
                }
            }
        }

        return $defaultPercent;
    }

    private function getCompanyLimitCreditCategory($creditTypeName)
    {
        $creditTypeName = strtolower($creditTypeName);
        $budgetNeedles = ['byujet', 'byudjet', 'budjet', 'budget', 'davlat'];
        foreach ($budgetNeedles as $needle) {
            if (strpos($creditTypeName, $needle) !== false) {
                return 'budget';
            }
        }

        $passportNeedles = ['passport', 'passaport', 'pasport'];
        foreach ($passportNeedles as $needle) {
            if (strpos($creditTypeName, $needle) !== false) {
                return 'passport';
            }
        }

        return 'default';
    }

    private function getCompanyCreditLimitStatistic()
    {
        return (new Query())
            ->select([
                'company_id' => 'co.id',
                'company_name' => 'co.name',
                'limit' => 'cpl.limit',
                'credit_type_id' => 'c.credit_type_id',
                'credit_type_name' => 'ct.name',
                'summa' => new Expression('COALESCE(SUM(c.doc_total_price), 0)'),
            ])
            ->from(['co' => 'company'])
            ->innerJoin(['cpl' => 'company_plan_limit'], [
                'and',
                'cpl.company_id = co.id',
                ['cpl.type' => CompanyPlanLimit::TYPE_CONTRACTS],
                ['cpl.status' => 1],
            ])
            ->leftJoin(['c' => 'credit'], [
                'and',
                'c.company_id = co.id',
                ['<>', 'c.credit_status', -2],
                ['c.rejected' => 0],
            ])
            ->leftJoin(['ct' => 'credit_type'], 'ct.id = c.credit_type_id')
            ->groupBy(['co.id', 'co.name', 'cpl.limit', 'c.credit_type_id', 'ct.name'])
            ->orderBy(['co.name' => SORT_ASC, 'ct.name' => SORT_ASC])
            ->all();
    }

    private function getCompanyPaymentLimitStatistic()
    {
        return (new Query())
            ->select([
                'company_id' => 'co.id',
                'company_name' => 'co.name',
                'limit' => 'cpl.limit',
                'credit_type_id' => 'p.credit_type_id',
                'credit_type_name' => 'ct.name',
                'summa' => new Expression('COALESCE(SUM(p.amount), 0)'),
            ])
            ->from(['co' => 'company'])
            ->innerJoin(['cpl' => 'company_plan_limit'], [
                'and',
                'cpl.company_id = co.id',
                ['cpl.type' => CompanyPlanLimit::TYPE_PAYMENTS],
                ['cpl.status' => 1],
            ])
            ->leftJoin(['p' => 'payments'], 'p.company_id = co.id')
            ->leftJoin(['ct' => 'credit_type'], 'ct.id = p.credit_type_id')
            ->groupBy(['co.id', 'co.name', 'cpl.limit', 'p.credit_type_id', 'ct.name'])
            ->orderBy(['co.name' => SORT_ASC, 'ct.name' => SORT_ASC])
            ->all();
    }

    public function actionReport()
    {
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86400;
        $month_start = strtotime(date('Y-m-01'));
        $month_end = strtotime(date('Y-m-t'));
        $payment = Payments::find();
        $plan = CreditPlan::find();

        return $this->render('report', [
            /*TODAY*/
            'today_cash' => $payment->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['method_id' => 0])->sum('amount'),
            'today_card' => $payment->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['method_id' => 1])->sum('amount'),
            'today_plan_total' => $plan->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['pay_status' => 0])->sum('pay_summa'),
            /*ALL THE TIME*/
            'plan_total' => $plan->where(['or', 'pay_status=0', 'pay_status=1'])->sum('pay_summa'),
            'all_time_cash' => $payment->where(['method_id' => 0])->sum('amount'),
            'all_time_card' => $payment->where(['method_id' => 1])->sum('amount'),
        ]);
    }

    public function actionLawyer()
    {

        return $this->render('lawyer');
    }

    public function actionDept($algenix = 0)
    {
        $date_begin = date('01.01.2020');
        $date_end = date('t.m.Y');

        $paymentsSub = (new Query())
            ->select(['credit_plan_id', new Expression('SUM(amount) as amount')])
            ->from('payments')
            ->groupBy('credit_plan_id');

        $credits = (new Query())
            ->select([
                'c.id as credit_id',
                'cl.fullname',
                'cl.passport_pinfl',
                'cl.birthday',
                'cl.passport_numb',
                'cl.passport_enddate',
                'c.company_id',
                'c.pay_day',
                'c.doc_total_price',
                'c.content',
                'c.algenix_autopay',
                'cp.pay_summa',
                new Expression('(COALESCE(SUM(cp.pay_summa), 0) - COALESCE(SUM(p2.amount), 0)) AS month_ost'),
            ])
            ->addSelect([
                new Expression('(
                SELECT COALESCE(SUM(p3.amount), 0)
                FROM payments p3
                WHERE p3.credit_id = c.id
            ) AS total_paid'),
                new Expression('(
                c.doc_total_price - (
                    SELECT COALESCE(SUM(p3.amount), 0)
                    FROM payments p3
                    WHERE p3.credit_id = c.id
                )
            ) AS total_ost'),
            ])
            ->from(['c' => 'credit'])
            ->innerJoin(['cl' => 'client'], 'cl.id = c.client_id')
            ->innerJoin(['cp' => 'credit_plan'], 'cp.credit_id = c.id')
            ->leftJoin(['p2' => $paymentsSub], 'p2.credit_plan_id = cp.id')
            ->filterWhere(['!=', 'c.credit_status', -2])
            ->andFilterWhere(['not like', 'c.content', 'Тест'])
            ->andFilterWhere(['cp.pay_status' => [0, 4, 5, 6]])
            ->andWhere(['c.rejected' => 0])
            ->groupBy('c.id')
            ->orderBy([
                'c.company_id' => SORT_ASC,
                new Expression('DAY(c.pay_day) ASC')
            ]);
        if ($algenix == 1) {
            $credits->andFilterWhere(['c.algenix_autopay' => $algenix]);
        }
        // Применение фильтра по дате, если указаны параметры Search
        if (($get = Yii::$app->request->get('Search')) || ($get = Yii::$app->request->post('Search'))) {
            $date_begin = $get['start'];
            $date_end = $get['end'];
            if (array_key_exists('id', $get)) {

                $credits->andFilterWhere(['c.id' => $get['id']]);
            }
        }
        $credits->andFilterWhere(['between', 'cp.created', strtotime($date_begin), strtotime($date_end)]);

        $data = $credits->all();
        // 🔥 Разделение fullname → last_name, first_name, middle_name
        foreach ($data as &$item) {
            $fio = trim($item['fullname']);
            $parts = preg_split('/\s+/', $fio);
            $item['last_name'] = $parts[0] ?? '';
            $item['first_name'] = $parts[1] ?? '';
            $item['middle_name'] = $parts[2] ?? 'XXX';
            $item['passport_begindate'] = date('Y-m-d', strtotime("-10 year", strtotime($item['passport_enddate'])));
        }
        unset($item); // на всякий случай
        $title = "Список должников: {$date_begin} - {$date_end}";

        // Обработка экспорта: если пришел POST
        if (Yii::$app->request->isPost) {

            // Проверяем, были ли отправлены отмеченные credit_id
            $selected_ids = Yii::$app->request->post('CreditId');

            if (!empty($selected_ids)) {
                // Оставляем только выбранные записи с указанными ID
                $data = array_filter($data, function ($credit) use ($selected_ids) {
                    return in_array($credit['credit_id'], $selected_ids);
                });
            }
            // Вызываем экспорт в Excel (только для отфильтрованных данных)
            $this->exportExcel($data, $title);
        }
        // Отображение представления с таблицей (для GET-запросов)
        return $this->render('dept_report', [
            'credits' => $data,
            'title' => $title,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ]);
    }

    private function exportExcel($credits, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки столбцов Excel
        $headers = [
            //'A1' => '#',
            'A1' => 'ISM',
            'B1' => 'FAMILIYA',
            'C1' => 'OTASINING ISMI',
            'D1' => 'JSHSHR',
            'E1' => 'TUG’ILGAN SANA',
            'F1' => 'PASSPORT',
            'G1' => 'PASSPORT BERILGAN SANA',
            'H1' => 'FILIAL ID',
            'I1' => 'TO’LOV KUNI',
            'J1' => 'OYLIK TO’LOVI',
            'K1' => 'JORIY OYGA QARZ',
            'L1' => 'JAMI QARZ',
            'M1' => 'EXT_ID',
            'N1' => 'IZOH',
        ];
        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }

        // Заполнение данных строками
        $row = 2;
        $i = 1;
        foreach ($credits as $credit) {
            if ($credit['total_ost'] < 100) continue;  // пропускаем, если долг < 100
            //$sheet->setCellValue("A{$row}", $i++);
            $sheet->setCellValue("A{$row}", $credit['first_name']);
            $sheet->setCellValue("B{$row}", $credit['last_name']);
            $sheet->setCellValue("C{$row}", $credit['middle_name']);
            $sheet->setCellValue("D{$row}", $credit['passport_pinfl']);
            $sheet->setCellValue("E{$row}", $credit['birthday']);
            $sheet->setCellValue("F{$row}", $credit['passport_numb']);
            $sheet->setCellValue("G{$row}", $credit['passport_begindate']);
            $sheet->setCellValue("H{$row}", $credit['company_id']);
            $sheet->setCellValue("I{$row}", Yii::$app->formatter->asDate($credit['pay_day'], 'php:j'));
            $sheet->setCellValue("J{$row}", Yii::$app->formatter->asDecimal($credit['pay_summa'], 0));
            $sheet->setCellValue("K{$row}", Yii::$app->formatter->asDecimal($credit['month_ost'], 0));
            $sheet->setCellValue("L{$row}", Yii::$app->formatter->asDecimal($credit['total_ost'], 0));
            $sheet->setCellValue("M{$row}", $credit['credit_id']);
            $sheet->setCellValue("N{$row}", (empty($credit['content'])) ? '-' : $credit['content']);
            $row++;
        }

        // Отправка файла Excel в браузер
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}.xlsx\"");
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function actionUpdateAutopay()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
        \Yii::info($_POST, 'debug');
        \Yii::info(\Yii::$app->request->bodyParams, 'debug');
        \Yii::info(\Yii::$app->request->getRawBody(), 'debug');
        \Yii::info(\Yii::$app->request->post(), 'debug');
        $model = \common\models\Credit::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Не найдено'];
        }

        $model->algenix_autopay = (int)$status;
        if ($status == 0) {
            $model->algenix_autopay_locked = 1;
        }
        if ($model->save(false)) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    private function debug($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }
}
