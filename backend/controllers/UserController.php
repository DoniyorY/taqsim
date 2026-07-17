<?php

namespace backend\controllers;

use common\models\User;
use common\models\UserMenuItem;
use common\models\search\UserSearch;
use common\models\search\UserMenuItemSearch;
use frontend\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
    * Lists all User models.
    *
    * @return string
    */
   public function actionIndex()
   {
      $searchModel = new UserSearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      
      return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
      ]);
   }
   
   public function actionChangePass()
   {
      $user = \common\models\User::findOne(['id' => 56]);
      $user->setPassword('');
      $user->save(false);
      return $this->redirect(Yii::$app->request->referrer);
   }
   
   /**
    * Displays a single User model.
    * @param int $id
    * @return string
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionView($id)
   {
      $model = $this->findModel($id);
      $searchModel = new UserMenuItemSearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      $items = \common\models\UserMenu::find()->orderBy('prior ASC')->all();
      $userMenu = new UserMenuItem();
      $result = UserMenuItem::findOne(['user_id' => $model->id]);
      if ($userMenu->load(Yii::$app->request->post())) {
         if (!empty($result)) {
            $result->delete();
         }
         if (isset($_POST['UserMenuItem']['links'])) {
            $arr = [];
            foreach ($_POST['UserMenuItem']['links'] as $item) {
               if ($item != 0) {
                  array_push($arr, $item);
               }
               $userMenu->link = implode(',', $arr);
               
            }
            
         } else {
            $userMenu->link = 0;
            
         }
         
         $userMenu->created = time();
         $userMenu->user_id = $model->id;
         $userMenu->content = 0;
         $userMenu->save(false);
         return $this->refresh();
         
      }
      return $this->render('view', [
         'model' => $model,
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
         'userMenu' => $userMenu,
         'items' => $items,
         'result' => $result,
      ]);
   }
   
   public function actionStatus($id, $status)
   {
      
      $model = $this->findModel($id);
      $model->status = $status;
      if ($model->save(false)) {
         return $this->redirect(['index']);
      }
      
   }
   
   /**
    * Creates a new User model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return string|\yii\web\Response
    */
   public function actionCreate()
   {
      $model = new SignupForm();
      if ($model->load(Yii::$app->request->post())) {
         $user = $model->signup();
         Yii::$app->session->setFlash('success', 'Новый пользователь уcпешно добавлен');
         if (is_null($user)){
            throw new HttpException(500,'Ошибка при добавлении пользователя');
         }
         return $this->redirect(['view', 'id' => $user->id]);
      }
      
      return $this->render('create', [
         'model' => $model,
      ]);
   }
   
   /**
    * Updates an existing User model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param int $id
    * @return string|\yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionUpdate($id)
   {
      $model = $this->findModel($id);
      
      if ($this->request->isPost && $model->load($this->request->post())) {
         $model->updated_at = time();
         $model->save(false);
         
         return $this->redirect(['view', 'id' => $model->id]);
      }
      
      return $this->render('update', [
         'model' => $model,
      ]);
   }
   
   /**
    * Deletes an existing User model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param int $id
    * @return \yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionDelete($id)
   {
      $this->findModel($id)->delete();
      
      return $this->redirect(['index']);
   }
   
   /**
    * Finds the User model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param int $id
    * @return User the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
      if (($model = User::findOne(['id' => $id])) !== null) {
         return $model;
      }
      
      throw new NotFoundHttpException('The requested page does not exist.');
   }
}
