<?php

namespace frontend\controllers;

use common\models\CreditPlan;
use common\models\ClientPhones;
use common\models\Payments;
use common\models\search\CreditPlanSearch;
use Yii;
use common\models\LawyerData;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function Zxing\Qrcode\Detector\find;

/**
 * CreditPlanController implements the CRUD actions for CreditPlan model.
 */
class CreditPlanController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CreditPlan models.
     *
     * @return string
     */
    public function actionMlan()
    {


        $old_model = \common\models\old\Plan::find()
            ->where('status=0 AND created<' . time())
            ->orderby('credit_id ASC')
            ->all();

        $new_model = \common\models\CreditPlan::find()
            ->where(['is_stopped' => 0, 'pay_status' => 0])
            ->andWhere(['<', 'created', time()])
            ->orderby('credit_id ASC')
            ->all();

        return $this->render('_past_', [
            'old_model' => $old_model,
            'new_model' => $new_model,
        ]);
    }

    /*public function actionReturnPlans()
    {

        $plan = CreditPlan::find()->where(['pay_status' => 4])->all();
        $days = strtotime("-20 days");
        print_r(date('d.m.Y', $days));
        die();
        foreach ($plan as $p) {
            $p->pay_status = 0;
            $p->yurist_goday = 0;
            $p->update(false);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }*/

    public function actionToday()
    {
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86399;

        $searchModel = new CreditPlanSearch();
        $searchModel->pay_status = 0;
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andFilterWhere(['between', 'plan.created', $dayStart, $dayEnd]);

        $today_payments = \common\models\Payments::find()
            ->where(['payment_type' => 0])
            ->andWhere(['between', 'created', $dayStart, $dayEnd])
            ->sum('amount');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'today_payments' => $today_payments,
        ]);
    }

    /* public function actionNullable()
     {
         $model=\common\models\Credit::findOne(['id'=>15908]);
         $model->credit_status=0;
         $model->update(false);
         return $this->redirect(Yii::$app->request->referrer);
     }*/
    public function actionFuture()
    {

        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86400;


        $searchModel = new CreditPlanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if ($get = Yii::$app->request->get('Search')) {
            $dataProvider->query->andFilterWhere(['between', 'plan.created', strtotime($get['begin_date']), strtotime($get['end_date']) + 86399]);
        }else{
            $dataProvider->query->andWhere(['>=', 'plan.created', $dayEnd]);
        }
        $dataProvider->query->andWhere(['=', 'pay_status', 0]);
        $sumQuery = clone $dataProvider->query;

        $total = $sumQuery
            ->limit(null)
            ->offset(null)
            ->orderBy(null)
            ->sum('plan.pay_summa');
        return $this->render('index_future', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total'=>$total
        ]);
    }
    protected function findTotal($data)
    {
        $total = 0;
        foreach ($data->all() as $one){
            $payment = (new Query())->select(['amount'])->from('payments')
                ->where(['credit_id' => $one->credit_id, 'credit_plan_id' => $one->id])
                ->sum('amount');
            $total+=$one->pay_summa - $payment??0;
        }
        return $total;
    }

    public function actionLate($days = null)
    {

        $searchModel = new CreditPlanSearch();
        $dataProvider = $searchModel->searchlate($this->request->queryParams);
        //$dataProvider->query->orderBy(['plan.created' => SORT_DESC]);
        $title = '';
        if ($days) {
            $last_days = strtotime("-$days days");
            $dataProvider->query->andWhere(['between', 'plan.created', $last_days, time()])->andWhere(['pay_status' => 0]);
            $t = (Yii::$app->language == 'ru') ? 'Дней' : 'Kun';
            $title = " ( $days $t ) ";
        }

        if (Yii::$app->request->get('CreditPlan')) {
            $begin = strtotime(Yii::$app->request->get('CreditPlan')['date_begin']);
            $end = strtotime(Yii::$app->request->get('CreditPlan')['date_end']);
            $end += 86399;
            $dataProvider->query->andFilterWhere(['between', 'plan.created', $begin, $end])->andWhere(['pay_status' => 0]);
        }

        $ids = \yii\helpers\ArrayHelper::getColumn($dataProvider->getModels(), 'client_id');
        $sumQuery = clone $dataProvider->query;
        $queryCount = clone $dataProvider->query;
        $totalCount = $queryCount->groupBy(['plan.credit_id'])->count();
        $total = $sumQuery
            ->limit(null)
            ->offset(null)
            ->orderBy(null)
            ->sum('plan.pay_summa');

        $extra_phone = ClientPhones::findAll(['client_id' => $ids]);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('client_info_modal', [
                'item' => $dataProvider->getModels(),
            ]);
        }

        return $this->render('index_past', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $this->findTotal($sumQuery),
            'extra_phone' => $extra_phone,
            'model' => new CreditPlan(),
            'title' => $title,
            'totalCount'=>$totalCount
        ]);
    }

    public function actionFixStatus()
    {
        $model = \common\models\Credit::findAll(['credit_status'=>4]);
        foreach ($model as $item){
            $item->credit_status=6;
            $item->save(false);

        }
    }
    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);
        $lawyer = LawyerData::findOne(['credit_id' => $model->credit_id]);
        if (isset($lawyer)) {
            if ($status == 5) {
                $lawyer->updated_judgement = time();
                $lawyer->user_judgement = Yii::$app->user->id;
                $lawyer->status = 1;
                $lawyer->update(false);
            } elseif ($status == 6) {
                $lawyer->updated_consideration = time();
                $lawyer->user_consideration = Yii::$app->user->id;
                $lawyer->status = 2;
                $lawyer->update(false);
            } elseif ($status == 7) {
                $lawyer->updated_finished = time();
                $lawyer->user_finished = Yii::$app->user->id;
                $lawyer->status = 3;
                $lawyer->update(false);
            }
        } else {
            $lawyer = new LawyerData();
            $lawyer->user_new = Yii::$app->user->id;
            $lawyer->user_consideration = null;
            $lawyer->user_finished = null;
            $lawyer->user_judgement = null;
            $lawyer->updated_new = null;
            $lawyer->updated_consideration = null;
            $lawyer->updated_finished = null;
            $lawyer->updated_judgement = null;
            $lawyer->status = 0;
            $lawyer->credit_id = $model->credit_id;
            $lawyer->save(false);
        }
        $model->pay_status = $status;
        $models = CreditPlan::findAll(['credit_id'=>$model->credit_id]);
        $model->update(false);
        foreach ($models as $item){
            $item->pay_status = $status;
            $item->save(false);
        }
        $model->credit->credit_status = 6;
        $model->credit->update(false);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSmsStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->is_sent_sms = $status;
        $model->update(false);
        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(\Yii::$app->request->post())) {
            $content_history = new \common\models\CreditPlanContents();
            $content_history->created = time();
            $content_history->content = $model->content;
            $content_history->credit_plan_id = $model->id;
            $content_history->credit_id = $model->credit_id;
            $content_history->save(false);
            $model->update(false);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionSendToLawyer($id)
    {
        $model = $this->findModel($id);
        $model->pay_status = 4;
        $model->yurist_goday = time();
        $model->is_sent_sms = 1;
        $model->update();
        /*  $model->credit->credit_status = 4;
          $model->credit->update(false);*/
        $lawyer_data = new LawyerData();
        if (!Yii::$app->user->isGuest) {
            $lawyer_data->user_new = Yii::$app->user->id;
        } else {
            $lawyer_data->user_new = 1;
        }
        $lawyer_data->credit_id = $model->credit_id;
        $lawyer_data->updated_new = time();
        $lawyer_data->status = 0;
        $lawyer_data->save(false);

        Yii::$app->session->setFlash('success', 'Отправлен юристу');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the CreditPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CreditPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CreditPlan::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
