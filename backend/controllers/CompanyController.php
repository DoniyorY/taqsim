<?php

namespace backend\controllers;

use common\models\Company;
use common\models\search\CompanySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index', 'create', 'view'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'view'],
                            'allow' => false,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => ['index', 'create', 'view'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],

                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Company models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->update(false);
        \Yii::$app->session->setFlash('success', 'Статус успешно изменен');
        return $this->redirect(\Yii::$app->request->referrer);

    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Company();
        $model->company_props = '<p style="line-height:1;"><strong>Nomi: OOO "YEC CARPETS"</strong></p>
<p style="line-height:1;"><strong>Manzil: A.T.XO\'QANDIY MAVZESI 105-UY 73 XONADON</strong></p>
<p style="line-height:1;"><strong>Tel: +998 98 130 99 99</strong></p>
<p style="line-height:1;"><strong>Tel: +998 90 366 24 24</strong></p>
<p style="line-height:1;"><strong>STIR: 307927952</strong></p>
<p style="line-height:1;"><strong>OKED: 47190</strong></p>
<p style="line-height:1;"><strong>H/R: 20208000005308134001</strong></p>
<p style="line-height:1;"><strong>Bank: КУКОН Ш., "INVEST FINANCE BANK" АТ БАНКИНИНГ КУКОН ФИЛИАЛИ</strong></p>
<p style="line-height:1;"><strong>MFO: 01116</strong></p>';
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->status = 0;
                $model->save();
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdateAll()
    {
        $com = '<p style="line-height:1;"><strong>Nomi: OOO "YEC CARPETS"</strong></p>
<p style="line-height:1;"><strong>Manzil: A.T.XO\'QANDIY MAVZESI 105-UY 73 XONADON</strong></p>
<p style="line-height:1;"><strong>Tel: +998 98 130 99 99</strong></p>
<p style="line-height:1;"><strong>Tel: +998 90 366 24 24</strong></p>
<p style="line-height:1;"><strong>STIR: 307927952</strong></p>
<p style="line-height:1;"><strong>OKED: 47190</strong></p>
<p style="line-height:1;"><strong>H/R: 20208000005308134001</strong></p>
<p style="line-height:1;"><strong>Bank: КУКОН Ш., "INVEST FINANCE BANK" АТ БАНКИНИНГ КУКОН ФИЛИАЛИ</strong></p>
<p style="line-height:1;"><strong>MFO: 01116</strong></p>';
        $model = Company::updateAll(['company_props'=>$com]);
        return $this->redirect(['index']);

    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Company model.
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

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
