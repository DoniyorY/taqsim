<?php

namespace frontend\controllers;

use common\models\CreditPlan;
use common\models\ClientPhones;
use common\models\Payments;
use common\models\search\CreditPlanSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CreditPlanController implements the CRUD actions for CreditPlan model.
 */
class CheckController extends Controller
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
    public function actionDe(){
        \common\models\CreditPlan::deleteAll(['credit_id'=>8234]);
        \common\models\CreditPlan::deleteAll(['credit_id'=>8287]);
    }

    public function actionLate(){

        $allplans=CreditPlan::find()
            ->where(['pay_status'=>0])
            // ->andWhere(['between', 'id', 100001, 110000 ])
            //->andWhere(['between', 'id', 90001, 100000 ])
            //->andWhere(['between', 'id', 80001, 90000 ])
            //->andWhere(['between', 'id', 70001, 80000 ])
            //->andWhere(['between', 'id', 60001, 70000 ])
            //->andWhere(['between', 'id', 50001, 60000 ])
            //->andWhere(['between', 'id', 40001, 50000 ])
            //->andWhere(['between', 'id', 30001, 40000 ])
            //->andWhere(['between', 'id', 20001, 30000 ])
            //->andWhere(['between', 'id', 10001, 20000 ])
           // ->andWhere(['between', 'id', 1, 10000 ])
           // ->andWhere(['>=', 'created', date('d.m.Y')])
            ->all();
        return $this->render('index_past', [
            'allplans'=>$allplans,
        ]);
    }
    public function actionStatus($id, $status){
        $model = $this->findModel($id);
        $model->is_sent_sms = $status;
        $model->update(false);
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(\Yii::$app->request->post()) && $model->update(false)){
            return $this->redirect(Yii::$app->request->referrer);
        }
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
