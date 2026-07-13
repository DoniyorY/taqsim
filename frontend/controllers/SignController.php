<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\Credit;
use common\models\CreditSign;
use yii\web\UploadedFile;

class SignController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'search', 'view', 'view-old', 'add-photo'],
                'rules' => [
                    [
                        'actions' => ['index', 'search', 'view', 'view-old', 'add-photo'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'search', 'view', 'view-old', 'add-photo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $layout = 'sign.php';
    public $defaultAction = 'index';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSearch()
    {
        $get = Yii::$app->request->get('credit');

        $credit = Credit::findOne(['id' => $get]);
        if (empty($credit)) {
            Yii::$app->session->setFlash('error', Yii::$app->params['labels_credit_id'][Yii::$app->language] . $get . Yii::$app->params['sign_error_find'][Yii::$app->language]);
            return $this->redirect(['index']);
        } else {
            $model = CreditSign::findOne(['credit_id' => $credit->id]);

            if (empty($model)) {
                $model = new CreditSign();
                $model->credit_id = $credit->id;
                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


    }

    public function actionSign($id)
    {
        $model = CreditSign::findOne(['id' => $id]);
        if ((Yii::$app->request->post('creditor_sign') != null)) {
            $model->client_sign = Yii::$app->request->post('creditor_sign');
            Yii::$app->session->setFlash('success', Yii::$app->params['sign_success'][Yii::$app->language]);
            $model->update(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } elseif ((Yii::$app->request->post('guarantor_sign') != null)) {
            $model->guarantor_sign = Yii::$app->request->post('guarantor_sign');
            $model->update(false);
            Yii::$app->session->setFlash('success', Yii::$app->params['sign_success'][Yii::$app->language]);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', Yii::$app->params['sign_error'][Yii::$app->language]);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionView($id)
    {
        //return $this->actionViewOld($id);
        $model = CreditSign::findOne(['id' => $id]);
        $photo = new \common\models\ClientCurrentPhoto();
        $photos = \common\models\ClientCurrentPhoto::findOne(['credit_id' => $model->credit_id]);
        return $this->render('view_new', [
            'model' => $model,
            'photo' => $photo,
            'photos' => $photos
        ]);
    }

    public function actionViewOld($id)
    {
        $model = CreditSign::findOne(['id' => $id]);
        return $this->render('view_old', [
            'model' => $model,
        ]);
    }

    public function actionAddPhoto()
    {
        $model = new \common\models\ClientCurrentPhoto(['created' => time(), 'user_id' => Yii::$app->user->id]);
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'imageFile');
            if ($file && $file->tempName) {
                $model->imageFile = $file;
                if ($model->validate(['file'])) {
                    $dir = Yii::getAlias('@frontend/web/uploads/client_current_photos/');
                    $name = time();
                    $fileName = $name . '.' . $model->imageFile->extension;
                    $model->imageFile->saveAs($dir . $fileName);
                    $model->imageFile = $fileName;
                    $model->image = $fileName;
                }
            }
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Изображение успешно добавлено!!!');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionDeletePhoto($photo_id)
    {
        $model = \common\models\ClientCurrentPhoto::findOne(['id'=>$photo_id]);
        $model->delete();
        Yii::$app->session->setFlash('warning','Фотография была удалена!!!');
        return $this->redirect(Yii::$app->request->referrer);
    }

}
