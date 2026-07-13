<?php

namespace frontend\controllers;

use Yii;
use common\models\Payments;
use common\models\search\PaymentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentsController implements the CRUD actions for Payments model.
 */
class PaymentsController extends Controller
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
     * Lists all Payments models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->orderBy(['created' => SORT_DESC]);
        $total = 0;
        $card = 0;
        $cash = 0;
        $atmos = 0;
        $algenix = 0;
        $mib = 0;
        if (Yii::$app->request->get('PaymentsSearch') and !empty($_GET['PaymentsSearch']['date_begin'])) {
            $paymentsSearch = $_GET['PaymentsSearch'];
            $date_begin = strtotime($paymentsSearch['date_begin']);
            $date_end = strtotime($paymentsSearch['date_end']) + 86399;
            $company_id = $paymentsSearch['company_id'];
            if (isset($company_id)) {
                $cash = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 0, 'company_id' => $company_id, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $card = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 1, 'company_id' => $company_id, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $atmos = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 2, 'company_id' => $company_id, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $algenix = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 3, 'company_id' => $company_id, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $mib = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 4, 'company_id' => $company_id, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
            } else {
                $cash = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 0, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $card = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 1, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $atmos = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 2, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $algenix = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 3, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
                $mib = Payments::find()->where(['between', 'created', $date_begin, $date_end])
                    ->andFilterWhere(['method_id' => 4, 'credit_type_id' => $paymentsSearch['credit_type_id']])->sum('amount');
            }
            $total = intval($card) + intval($cash) + intval($atmos) + intval($algenix) + intval($mib);
        }

        //rasxod s kassi
        $model_create = new Payments();
        if ($this->request->isPost) {
            if ($model_create->load($this->request->post())) {
                $model_create->created = time();
                $model_create->user_id = yii::$app->user->identity->id;
                $model_create->credit_id = 0;
                $model_create->credit_type_id = 0;
                $model_create->credit_plan_id = 0;
                $model_create->pay_type = 3;
                $model_create->payment_type = 1;
                $model_create->company_id = 0;
                $model_create->amount = $model_create->amount * -1;
                if ($model_create->save()) {
                    return $this->refresh();
                }

            }
        } else {
            $model_create->loadDefaultValues();
        }

        return $this->render('index', [
            'model_create' => $model_create,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total,
            'card' => $card,
            'cash' => $cash,
            'atmos' => $atmos,
            'algenix' => $algenix,
            'mib'=>$mib
            
        ]);
    }

    public function actionLawyerIncome()
    {
        $model = new Payments();
        if ($this->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->created = time();
                $model->payment_type = 0;
                $model->pay_type = 2;
                $model->credit_plan_id = 0;
                $model->user_id = Yii::$app->user->id;
                $model->credit_type_id = $model->credit->credit_type_id;
                $model->save();
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            $model->loadDefaultValues();
        }
    }

    public function actionJsonc($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('id, doc_date_start')
                ->from('credit')
                ->where(['id' => $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $pat_model = \common\models\Credit::findOne(['id' => $id]);
            $out['results'] = [
                'id' => $id,
                'text' => $pat_model->doc_date_start,

            ];
        }
        return $out;
    }

    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }*/

    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $basket = new \common\models\PaymentBasket([
                'payment_id' => $id,
                'payment_created' => $model->created,
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
                'deleted_user_id' => Yii::$app->user->id,
                'deleted_time' => time(),
            ]);
            $basket->save(false);
            $model->delete();
            Yii::$app->session->setFlash('warning','Оплата успешно удалена!!');
            $transaction->commit();
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    /**
     * Finds the Payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payments::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
