<?php

namespace frontend\controllers;

use common\models\PaymentBasket;
use common\models\search\PaymentBasketSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentBasketController implements the CRUD actions for PaymentBasket model.
 */
class PaymentBasketController extends Controller
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
     * Lists all PaymentBasket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentBasketSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReturnPayment($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $payment = new \common\models\Payments([
                'created' => $model->payment_created,
                'payment_type' => $model->payment_type,
                'method_id' => $model->method_id,
                'pay_type' => $model->pay_type,
                'company_id' => $model->company_id,
                'content' => $model->content,
                'credit_plan_id' => $model->credit_plan_id,
                'user_id' => $model->user_id,
                'credit_id' => $model->credit_id,
                'credit_type_id' => $model->credit_type_id,
                'amount' => $model->amount,
            ]);
            $payment->save(false);
            $model->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success','Оплата успешно восстановлена');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    /**
     * Finds the PaymentBasket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PaymentBasket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentBasket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
