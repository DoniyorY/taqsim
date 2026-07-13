<?php

namespace frontend\controllers;

use common\models\Credit;
use common\models\Payments;
use common\models\CreditItem;
use common\models\CreditInvoice;
use common\models\Client;
use common\models\CreditSign;
use common\models\CreditPlan;
use common\models\search\CreditSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CreditController implements the CRUD actions for Credit model.
 */
class CreditController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index', 'create', 'view', 'search'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'view', 'search'],
                            'allow' => false,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => ['index', 'create', 'view', 'search'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'to-basket' => ['POST'],
                        'reset-basket' => ['POST'],
                        'delete' => ['POST'],
                        //'status' => ['POST'],
                        'delete-plan' => ['POST'],
                        //'fix-credit' => ['post'],
                        'payment' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Credit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CreditSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
         if ($get= Yii::$app->request->get('Period')){
            $dataProvider->query->andWhere(['between', 'credit.created', strtotime($get['begin_date']), strtotime($get['end_date'])+86399]);
         }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => \Yii::$app->params['credits'][\Yii::$app->language],
        ]);
    }


    public function actionPlaymobile()
    {
        $p = \Yii::$app->playmobile->sendSms(998995993603, 'Hello');
        //$p=0;
        echo "<pre>";
        print_r($p);
    }

    /*public function actionFixPlans()
    {
        $plans = \common\models\CreditPlan::findAll(['credit_id' => 25743]);
        $t = Yii::$app->db->beginTransaction();
        try {
            foreach ($plans as $plan) {
                $plan->created = strtotime("+1 year", $plan->created);
                $plan->save(false);
            }
            $t->commit();
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $e) {
            $t->rollBack();
            echo "<pre>";
            print_r($e);
            die();
        }
    }*/


    public function actionToBasket($id)
    {
        if ($this->request->isPost) {
            $model = Credit::findOne(['id' => $id]);
            $model->credit_status = -2;
            if ($model->update(false)) {
                $credit_plans = \common\models\CreditPlan::updateAll(
                    ['is_stopped' => 1], ['credit_id' => $model->id]
                );
                Yii::$app->session->setFlash('success', 'Договор отправлен в корзину');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /*public function actionFixCreditPayment()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $credit = Credit::findAll(['company_id' => 21]);
            $arr =[];
            foreach ($credit as $item) {
                $payments = Payments::find()
                    ->where(['credit_id' => $item->id])
                    ->andWhere(['company_id' => 18])
                    ->andWhere(['!=', 'amount', 0])
                    ->all();
                $arr[] = $payments;
                foreach ($payments as $p){
                    $p->company_id=$item->company_id;
                    $p->save(false);
                }
            }
            $transaction->commit();
            return $this->asJson($arr);
        }catch (\Exception $e){
            $transaction->rollBack();
        }

       // return $this->asJson($payments);

    }*/

    public function actionResetBasket($id)
    {
        if ($this->request->isPost) {
            $model = Credit::findOne(['id' => $id]);
            $model->credit_status = 2;
            if ($model->update(false)) {
                $credit_plans = \common\models\CreditPlan::updateAll(
                    ['is_stopped' => 0], ['credit_id' => $model->id]
                );
                Yii::$app->session->setFlash('success', 'Договор отправлен в корзину');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    public function actionBasket($id = null)
    {
        $searchModel = new CreditSearch();
        $searchModel->credit_status = -2;
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->orderBy(['id' => SORT_DESC]);


        return $this->render('basket', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => \Yii::$app->params['credits'][\Yii::$app->language],
        ]);
    }

    public function actionSearch($id)
    {

        $model = Credit::findOne(['id' => $id]);

        if (isset($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            \Yii::$app->session->setFlash('error', 'Договор № ' . $id . ' Не найдено');
            return $this->redirect(['index']);
        }
    }

    /**
     * Displays a single Credit model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionFix()
    {
        $model = CreditPlan::findAll(['credit_id' => 24353]);
        foreach ($model as $item) {
            $item->created = strtotime("-1 year", $item->created);
            $item->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $credit_item = CreditItem::findAll(['credit_id' => $id]);
        $credit_plans = CreditPlan::findAll(['credit_id' => $id]);
        $payment_history = Payments::findAll(['credit_id' => $id]);

        $payment = new Payments();
        if ($payment->load(\Yii::$app->request->post())) {
            $this->actionPayment($payment);
        }
        if ($this->request->isPost && $model->load($this->request->post())) {
            if (isset($model->method_id)) {
                $model->credit_status = 1;
                $model->update();
            } else {
                \Yii::$app->session->setFlash('error', 'Method is not selected');
            }

            return $this->refresh();
        }

        $signs = CreditSign::findOne(['credit_id' => $id]);

        return $this->render('view', [
            'model' => $model,
            'credit_item' => $credit_item,
            'credit_plans' => $credit_plans,
            'payment' => $payment,
            'payment_history' => $payment_history,
            'signs' => $signs,
        ]);
    }

    public function actionPayment(Payments $payment)
    {
        //$payment = new Payments();
        if ($payment->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $credit = Credit::find()
                    ->select(['id', 'client_id', 'created', 'credit_type_id', 'company_id'])
                    ->with('client') // чтобы не было повторного запроса на $credit->client
                    ->where(['id' => $payment->credit_id])
                    ->one();

                if (!$credit) {
                    throw new NotFoundHttpException('Кредит не найден');
                }
                $payment->company_id = $credit->company_id;
                $payment->created = time();
                $payment->user_id = Yii::$app->user->id;
                $payment->credit_id = $credit->id;
                $payment->payment_type = 0;
                $payment->credit_type_id = $credit->credit_type_id;
                $payment->pay_type = 1;

                $payment->save(false);

                // Получаем суммы через оптимизированные запросы
                $plan_amount = $this->getPlanAmountSum($credit->id);
                $payment_amount = $this->getPaymentsAmountSum($credit->id);
                $ostatok = $plan_amount - $payment_amount;

                $phone = $credit->client->phone;

                $message = 'Assalomu Aleykum xurmatli ' . $credit->client->fullname .
                    '  sizni LUX Gilam dukonidan N ' . $credit->id . ' sana ' . date('d.m.Y', $credit->created) . ' shartnomaga asosan ' .
                    Yii::$app->formatter->asDecimal($payment->amount, 0) . ' soʻm toʻlov summasi qabul qilindi. Qoldiq  ' .
                    Yii::$app->formatter->asDecimal($ostatok, 0) . ' soʻm. Kuningiz hayrli oʻtsin!';

                Yii::$app->playmobile->sendSms("+$phone", $message);
                if ($credit->algenix_autopay == 1) {
                    $algenix = \frontend\modules\api\controllers\AlgenixController::makeCreditPayment($credit->id, $payment->amount, $payment->content);
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new HttpException(400, $e->getMessage());
            }


            return $this->redirect(Yii::$app->request->referrer);
        }
        return 0;

    }


    protected function getPlanAmountSum($creditId)
    {
        return (new Query())
            ->from('credit_plan')
            ->where(['credit_id' => $creditId])
            ->sum('pay_summa') ?: 0;
    }

    protected function getPaymentsAmountSum($creditId)
    {
        return (new Query())
            ->from('payments')
            ->where(['credit_id' => $creditId])
            ->sum('amount') ?: 0;
    }

    public function actionCreateItem($credit)
    {
        $model = new CreditItem();
        if ($model->load(\Yii::$app->request->post())) {
            $model->credit_id = $credit;
            $model->save();
            return $this->redirect(['view', 'id' => $credit]);
        }
    }

    public function actionGraphic($token)
    {
        $this->layout = 'sign.php';

        $model = Credit::findOne(['token' => $token]);
        $plan = CreditPlan::findAll(['credit_id' => $model->id]);
        $payment_history = Payments::findAll(['credit_id' => $model->id]);
        return $this->render('qr_graphic', [
            'model' => $model,
            'credit_plans' => $plan,
            'payment_history' => $payment_history,
        ]);
    }

    public function actionSign($credit_id)
    {
        $credit = $this->findModel($credit_id);
        $model = CreditSign::findOne(['credit_id' => $credit->id]);
        if (isset($model->credit)):
            return $this->render('sign', [
                'credit' => $credit,
                'model' => $model,
            ]);
        else:
            Yii::$app->session->setFlash('error', 'Подпись клиента не найден, проверьте подписи');
            return $this->redirect(Yii::$app->request->referrer);
        endif;
    }


    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);
        if (!is_null($status)) {
            $model->credit_status = $status;
        }
        if ($model->credit_status == 2) {
            $payment = new Payments();
            $payment->created = time();
            $payment->payment_type = 0;
            $payment->method_id = 0;
            $payment->pay_type = 0;
            $payment->company_id = $model->company_id;
            $payment->content = 'Аванс по договору № ' . $model->id . ' - ' . $model->doc_date_start;
            $payment->user_id = \Yii::$app->user->id;
            $payment->credit_id = $model->id;
            $payment->credit_type_id = $model->credit_type_id;
            $payment->amount = $model->prepaid_summa;
            $payment->save(false);
            \common\models\CreditPlan::updateAll(['pay_status' => 0], ['credit_id' => $model->id]);
            $phone = $model->client->phone;
            $msg = 'Assalomu aleykum xurmatli ' . $model->client->fullname . ' LUX Gilam dukoni bilan qonuniy
        N' . $model->id . ' sana: ' . date('d.m.Y') . ' shartnoma tuzildi. Xaridingiz uchun minnatdormiz. Kuningiz hayrli oʻtsin!';
            \Yii::$app->playmobile->sendSms("+$phone", $msg);
            /*$atmos = AtmosController::startSchedule($model);
            if (!$atmos) {
                Yii::$app->session->setFlash('warning', 'У клиента нет привязанных карт');
                return $this->redirect(Yii::$app->request->referrer);
            }*/

        }
        $model->update();
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPlanStatus($id, $status)
    {
        $model = CreditPlan::findOne(['id' => $id]);
        if (isset($status)) {
            $model->pay_status = $status;
            $model->update();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Credit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new Credit();
        $client = Client::findOne($id);
        $q1 = \Yii::$app->security->generateRandomString(2);
        $q2 = \Yii::$app->security->generateRandomString(2);
        $q3 = \Yii::$app->security->generateRandomString(2);
        $q4 = \Yii::$app->security->generateRandomString(2);
        $q5 = \Yii::$app->security->generateRandomString(2);
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->doc_date_start = Yii::$app->formatter->asDate($model->doc_date_start, "php:d.m.Y");
                $model->doc_date_end = Yii::$app->formatter->asDate($model->doc_date_end, "php:d.m.Y");
                $model->user_id = \Yii::$app->user->id;
                $model->client_id = $id;
                $model->token = $q1 . '-' . $q2 . '-' . $q3 . '-' . $q4 . '-' . $q5;
                $model->credit_status = 0;
                $model->created = time();

                $day = $model->pay_day;
                $year = date('Y');
                $month = date('m');

                // Формируем дату
                $fullDate = sprintf('%s-%s-%02d', $year, $month, $day);

                // сдвигаем на 1 месяц
                $nextMonth = date('Y-m-d', strtotime($fullDate . ' +1 month'));

                $model->pay_day = $nextMonth;

                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'client' => $client
        ]);
    }

    public function actionMakePlan($id)
    {
        $model = $this->findModel($id);
        $month = $model->month_count;
        //$summa = $model->doc_total_price;
        //$begin = strtotime($model->doc_date_start);
        $one_month = ($model->doc_total_price - $model->prepaid_summa) / $model->month_count;
        $pay_day = strtotime($model->pay_day);

        if ($model->credit_status === 1) {
            for ($i = 0; $i < $month; $i++) {
                $plan = new CreditPlan();
                $plan->credit_id = $model->id;
                $plan->company_id = $model->company_id;
                $plan->client_id = $model->client_id;
                $plan->created = strtotime("+$i month", $pay_day); //$pay_day + 2629743 * $i;
                $plan->pay_status = -1;
                $plan->pay_summa = $one_month;
                $plan->summa_bonus = $one_month * 20 / 100;
                $plan->summa_real = $one_month - $plan->summa_bonus;
                $plan->is_sent_sms = 1;
                $plan->is_stopped = 0;
                $plan->yurist_goday = 0;
                $plan->save(false);
            }
            $invoice = new CreditInvoice();
            $invoice->credit_id = $model->id;
            $invoice->created = time();
            $invoice->status = 0;
            $invoice->user_id = \Yii::$app->user->id;
            $invoice->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Updates an existing Credit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $day = $model->pay_day;
            $year = date('Y');
            $month = date('m');

            // Формируем дату
            $fullDate = sprintf('%s-%s-%02d', $year, $month, $day);

            // сдвигаем на 1 месяц
            $nextMonth = date('Y-m-d', strtotime($fullDate . ' +1 month'));

            $model->pay_day = $nextMonth;
            $model->update(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Credit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionItemDelete($id, $credit)
    {
        $model = CreditItem::findOne($id);
        $model->delete();
        return $this->redirect(['view', 'id' => $credit]);
    }

    public function actionDeletePlan($id)
    {
        if ($this->request->isPost) {
            $plan = CreditPlan::findOne(['id' => $id]);
            $plan->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionDeleteAll($id)
    {
        $credit = Credit::deleteAll(['id' => $id]);
        $plan = CreditPlan::deleteAll(['credit_id' => $id]);
        $invoice = CreditInvoice::deleteAll(['credit_id' => $id]);
        $item = CreditItem::deleteAll(['credit_id' => $id]);
        $sign = CreditSign::deleteAll(['credit_id' => $id]);
        $payments = Payments::deleteAll(['credit_id' => $id]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Credit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Credit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Credit::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRejected()
    {
        $searchModel = new CreditSearch();
        $dataProvider = $searchModel->searchRejected($this->request->queryParams);
        return $this->render('index_rejected', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => \Yii::$app->params['credits_rejected'][\Yii::$app->language],
        ]);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($this->request->isPost) {
                $model->rejected_reason = $_POST['Reject']['reject_reason'];
                $model->rejected = 1;
                $model->rejected_user_id = Yii::$app->user->id;
                $model->rejected_time = time();
                $model->credit_status = -1;
                $model->algenix_autopay = 0;
                $model->save(false);
                Yii::$app->session->setFlash('warning', 'Кредит отменен!!!');
                $tr->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } catch (\Exception $e) {
            $tr->rollBack();
            echo "<pre>";
            print_r($e);
            die();
        }
    }

    public function actionRevorke($id)
    {
        $model = $this->findModel($id);
        $model->credit_status = 1;
        $model->rejected = 0;
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Договор активен');
        return $this->redirect(Yii::$app->request->referrer);
    }

}
