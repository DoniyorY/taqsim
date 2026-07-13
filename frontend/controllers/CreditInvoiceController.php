<?php

namespace frontend\controllers;

use common\models\CreditInvoice;
use common\models\Credit;
use common\models\CreditSign;
use common\models\CreditPlan;
use common\models\CreditItem;
use common\models\search\CreditInvoiceSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CreditInvoiceController implements the CRUD actions for CreditInvoice model.
 */
class CreditInvoiceController extends Controller
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
     * Lists all CreditInvoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CreditInvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContract($id)
    {
        $credit = Credit::findOne($id);
        $credit_item = CreditItem::findAll(['credit_id' => $id]);
        $sign = CreditSign::findOne(['credit_id' => $id]);
        return $this->render('contract', [
            'model' => $credit,
            'items' => $credit_item,
            'sign' => $sign,

        ]);
    }

    public function actionGuarantor($id)
    {
        $credit = Credit::findOne($id);
        $credit_item = CreditItem::findAll(['credit_id' => $id]);
        $sign = CreditSign::findOne(['credit_id' => $credit->id]);
        return $this->render('guarantor_contract', [
            'model' => $credit,
            'items' => $credit_item,
            'sign' => $sign,
        ]);
    }

    public function actionPaymentPlan($id)
    {
        $credit = Credit::findOne($id);
        $credit_item = CreditItem::findAll(['credit_id' => $id]);
        $plan = CreditPlan::find()->where(['credit_id' => $id])->orderBy('created ASC')->all();
        $sign = CreditSign::findOne(['credit_id' => $credit->id]);
        return $this->render('payment_plan', [
            'model' => $credit,
            'items' => $credit_item,
            'plan' => $plan,
            'sign' => $sign
        ]);
    }

    public function actionLetter($id)
    {
        $credit = Credit::findOne($id);
        $items = \common\models\CreditItem::findAll(['credit_id' => $id]);
        return $this->render('letter', [
            'model' => $credit,
            'items' => $items,
        ]);
    }

    public function actionWarning($id, $plan_id)
    {
        $credit = Credit::findOne(['id' => $id]);
        $credit_plan = CreditPlan::findOne(['id' => $plan_id]);
        $credit_plans = CreditPlan::findAll(['credit_id' => $credit->id]);
        $sum = 0;
        $payment_summa = (new Query())->select(['sum(amount) as amount'])
            ->from('payments')
            ->where(['credit_id' => $credit->id])
            ->one();

        foreach ($credit_plans as $item) {
            $sum += $item->pay_summa;
        }
        $ost = $sum - $payment_summa['amount'];
        return $this->render('warning_letter', [
            'model' => $credit,
            'plan' => $credit_plan,
            'sum' => $ost,
        ]);
    }


    public function actionCheque($id)
    {
        return $this->render('cheque', [
            'model' => \common\models\Payments::findOne($id),
        ]);
    }

    /**
     * Displays a single CreditInvoice model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionView1($id)
    {
        return $this->render('view', [
            'model' => CreditInvoice::findOne(['credit_id' => $id]),
            'sign' => CreditSign::findOne(['credit_id' => $id]),
        ]);
    }


    /**
     * Finds the CreditInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CreditInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CreditInvoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
