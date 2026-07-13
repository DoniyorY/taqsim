<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use common\models\search\CreditPlanSearch;
use common\models\ClientPhones;
use common\models\CreditPlan;
use common\models\Payments;

class LawyerController extends Controller
{

    protected function findTotal($model)
    {
        //$model = CreditPlan::find()->where(['pay_status' => 4])->all();
        $total = 0;
        foreach ($model->all() as $one) {
            $payment = (new Query())->select(['amount'])->from('payments')
                ->where(['credit_id' => $one->credit_id, 'credit_plan_id' => $one->id])
                ->sum('amount');
            $total += $one->pay_summa - $payment ?? 0;
        }
        return $total;
    }

    public function actionIndex($id)
    {

        $searchModel = new CreditPlanSearch();
        $searchModel->pay_status = $id;
        $dataProvider = $searchModel->searchLawyer($this->request->queryParams);
        //$dataProvider->query->andWhere(['!=', 'credit.credit_type_id', 7]);
        $dataProvider->pagination->pageSize = 50;
        if ($get=Yii::$app->request->get('Search')){
            $dataProvider->query->andFilterWhere(['between','plan.created',strtotime($get['begin_date']),strtotime($get['end_date'])]);
        }
        $ids = \yii\helpers\ArrayHelper::getColumn($dataProvider->getModels(), 'client_id');
        $extra_phone = ClientPhones::findAll(['client_id' => $ids]);
        $sumQuery = clone $dataProvider->query;
        $totalQuery = clone $dataProvider->query;
        $totalCount = $totalQuery->groupBy(['plan.credit_id'])->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $this->findTotal($sumQuery),
            'extra_phone' => $extra_phone,
            'model' => new CreditPlan(),
            'status_id' => $id,
            'totalCount' => $totalCount,
        ]);
    }


    public function actionDeleteContent($content_id)
    {
        $model = \common\models\CreditPlanContents::findOne(['id' => $content_id]);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

}