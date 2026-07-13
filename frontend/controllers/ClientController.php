<?php

namespace frontend\controllers;

use common\models\Client;
use common\models\ClientCards;
use common\models\ClientPhones;
use common\models\Credit;
use common\models\CreditPlan;
use common\models\Payments;
use common\models\search\ClientSearch;
use Yii;
use yii\filters\AccessControl;
use frontend\controllers\AtmosController;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\CardCreditLink;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
                    'only' => ['index', 'view', 'check'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'check'],
                            'allow' => false,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'create-card' => ['POST'],
                        'disable-card' => ['POST'],
                        'to-blacklist' => ['post'],
                        'from-blacklist' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Client models.
     *
     * @return string
     */

    public function beforeAction($action)
    {
        if ($action->id == 'create') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionJsonc($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '', 'phone' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('id, fullname AS text, phone')
                ->from('client')
                ->where(['like', 'fullname', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $pat_model = \common\models\Client::findOne($id);
            $out['results'] = [
                'id' => $id,
                'text' => $pat_model->fullname,
                'phone' => $pat_model->phone,

            ];
        }
        return $out;
    }

    public function actionJsong($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '', 'credit_limit' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('id, fullname AS text, credit_limit')
                ->from('client')
                ->where(['like', 'fullname', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $pat_model = \common\models\Client::findOne($id);
            $out['results'] = [
                'id' => $id,
                'text' => $pat_model->fullname,
                'credit_limit' => Yii::$app->formatter->asDecimal($pat_model->credit_limit, 0),

            ];
        }
        return $out;
    }


    public function actionCreateCard()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new \common\models\ClientCards();
            if ($model->load(Yii::$app->request->post())) {

                $str = str_replace(' ', '', $model->card_number);
                [$month, $year] = explode('/', $model->card_date);
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $expiry_formatted = sprintf("%s%s", $year, $month);

                // Проверяем была ли добавлена эта карта
                $check = \common\models\ClientCards::findOne(['card_number' => $str, 'card_date' => $model->card_date]);
                if ($check && $check->status == 0) {
                    Yii::$app->session->setFlash('warning', 'This card is already exists!!');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    if ($check) $model = $check;
                    $model->created = time();
                    $model->status = 1;
                    $model->client_phone = null;
                    $model->token = null;
                    $model->card_number = intval($str);
                    if ($model->save(false)) {
                        $add_card = \frontend\modules\api\controllers\AlgenixController::addCard($model->client_id, $model);
                        if ($add_card['success']) {
                            $model->client_phone = $add_card['data']['data']['phone_number'];
                            $model->save(false);
                            $transaction->commit();
                            return $this->redirect(
                                [
                                    'confirm-phone',
                                    'session_id' => $add_card['data']['data']['session'],
                                    'client_id' => $model->client_id,
                                    'card_id' => $model->id,
                                    'branch_id' => $add_card['data']['data']['branch_id'],
                                    'otp_phone' => $add_card['data']['data']['otp_sent_phone']
                                ]);
                        } else {
                            $transaction->rollBack();
                            if (array_key_exists('message', $add_card)){
                                $message = $add_card['message'];
                            }else{
                                $message = "{$add_card['data']}";
                            }
                            Yii::$app->session->setFlash('danger', $message);
                        }
                    }
                }
                Yii::$app->session->setFlash('warning', 'Ошибка при добавлении карты');
                return $this->redirect(Yii::$app->request->referrer);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    public function actionConfirmPhone($session_id, $client_id, $card_id, $branch_id, $otp_phone = null)
    {
        $this->layout = 'sign.php';
        $model = \common\models\ClientCards::findOne(['id' => $card_id, 'status' => 1]);
        if (Yii::$app->request->post()) {
            $code = $_POST['confirm_code'];
            $data = [
                'session_id' => $session_id,
                'otp' => $code,
                'client_id' => $model->client_id,
                'branch_id' => $branch_id,
                'phone_number' => $model->client_phone,
            ];
            $confirm = \frontend\modules\api\controllers\AlgenixController::confirmCard($data);

            if ($confirm['success']) {
                $model->status = 0;
                $model->algenix_card_id = $confirm['data']['data']['client_card_id'];
                $model->save(false);
                Yii::$app->session->setFlash('success', 'Карта успешно привязана');
                return $this->redirect(['view', 'id' => $client_id]);
            } else {
                echo "<pre>";
                print_r($confirm);
                die();
            }
        }
        Yii::$app->session->setFlash('success', 'Смс код отправлен на номер телефона: ' . $otp_phone);
        return $this->render('confirm_phone', [
            's_id' => $session_id,
            'cl_id' => $client_id,
            'c_id' => $card_id,
            'branch_id' => $branch_id
        ]);
    }

    public function actionDisableCard($id)
    {
        $model = \common\models\ClientCards::findOne(['id' => $id]);
        $model->status = 1;
        $model->token = null;

        $atmos = AtmosController::disableCard($model->atmos_card_id, $model->token);
        ($atmos) ? $model->update(false) : Yii::$app->session->setFlash('warning', 'Карта не найдена в базе Атмос');
        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andFilterWhere(['client_type' => 0]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => Yii::$app->params['labels_clients'][Yii::$app->language],
        ]);
    }

    public function actionBlacklistIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andFilterWhere(['client_type' => 0, 'is_blacklist' => 1]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => Yii::$app->params['labels_in_blacklist'][Yii::$app->language],
        ]);
    }

    public function actionGuar()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andFilterWhere(['client_type' => 1]);

        return $this->render('guar_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => Yii::$app->params['labels_guarantors'][Yii::$app->language],
        ]);
    }

    /**
     * Displays a single Client model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $lang = Yii::$app->language;
        $model = $this->findModel($id);

        $credits = \common\models\Credit::find()->where(['client_id' => $model->id])->all();
        $ids = \yii\helpers\ArrayHelper::getColumn($credits, 'id');
        $payment = \common\models\Payments::find()->where(['credit_id' => $ids])->all();
        $view_phone = ClientPhones::findAll(['client_id' => $model->id]);
        $view_files = \common\models\ClientFiles::findAll(['client_id' => $model->id]);
        $cards = \common\models\ClientCards::findAll(['client_id' => $id]);
        $current_photos = \common\models\ClientCurrentPhoto::findAll(['client_id' => $model->id]);
        /*Adding new phones*/
        $client_phone = new ClientPhones();
        if ($client_phone->load(Yii::$app->request->post())) {
            $client_phone->created = time();
            $client_phone->client_id = $model->id;
            $client_phone->save();
            return $this->refresh();
        }
        /*Adding Extra Files*/
        $extra_files = new \common\models\ClientFiles();


        $title = '';
        if ($model->client_type == 0) {
            $title = Yii::$app->params['labels_clients'][$lang];
        } else {
            $title = Yii::$app->params['labels_guarantor'][$lang];
        }
        return $this->render('view', [
            'model' => $model,
            'client_phone' => $client_phone,
            'view_phone' => $view_phone,
            'payment' => $payment,
            'credits' => $credits,
            'title' => $title,
            'extra_files' => $extra_files,
            'view_files' => $view_files,
            'cards' => $cards,
            'current_photos' => $current_photos,
        ]);
    }

    public function actionTest()
    {
        $string = '{"USER":"www-data","HOME":"\/var\/www","HTTP_COOKIE":"_language=2c576fad2968901c4b8057e38474c996cb713e4858f10be729c7eb7c292551efa%3A2%3A%7Bi%3A0%3Bs%3A9%3A%22_language%22%3Bi%3A1%3Bs%3A2%3A%22ru%22%3B%7D; advanced-frontend=rmn91g181bpnq5nuuavt0h5rjn","HTTP_CONTENT_LENGTH":"24","HTTP_CONNECTION":"keep-alive","HTTP_ACCEPT_ENCODING":"gzip, deflate, br","HTTP_HOST":"taqsimsavdo.uz","HTTP_POSTMAN_TOKEN":"dfff89e2-7a6a-4e7d-b569-4f010fd14627","HTTP_CACHE_CONTROL":"no-cache","HTTP_ACCEPT":"*\/*","HTTP_USER_AGENT":"PostmanRuntime\/7.48.0","HTTP_AUTHORIZATION":"Basic eWVjR2lsYW06QWxnZW5peA==","HTTP_CONTENT_TYPE":"application\/json","REDIRECT_STATUS":"200","SERVER_NAME":"taqsimsavdo.uz","SERVER_PORT":"80","SERVER_ADDR":"95.47.238.245","REMOTE_USER":"yecGilam","REMOTE_PORT":"47712","REMOTE_ADDR":"94.230.230.119","SERVER_SOFTWARE":"nginx\/1.18.0","GATEWAY_INTERFACE":"CGI\/1.1","REQUEST_SCHEME":"http","SERVER_PROTOCOL":"HTTP\/1.1","DOCUMENT_ROOT":"\/home\/taqsimsavdo","DOCUMENT_URI":"\/frontend\/web\/index.php","REQUEST_URI":"\/ru\/api\/algenix\/get-debtors?branch_id=18&page=2&count=1","SCRIPT_NAME":"\/frontend\/web\/index.php","CONTENT_LENGTH":"24","CONTENT_TYPE":"application\/json","REQUEST_METHOD":"POST","QUERY_STRING":"branch_id=18&page=2&count=1","SCRIPT_FILENAME":"\/home\/taqsimsavdo\/frontend\/web\/index.php","PATH_INFO":"","FCGI_ROLE":"RESPONDER","PHP_SELF":"\/frontend\/web\/index.php","PHP_AUTH_USER":"yecGilam","PHP_AUTH_PW":"Algenix","REQUEST_TIME_FLOAT":1760513258.234755,"REQUEST_TIME":1760513258}';
        $data = json_decode($string, true);
        return $this->asJson($data);
    }

    public function actionExtraFile($id)
    {
        $model = new \common\models\ClientFiles();
        if ($model->load(Yii::$app->request->post())) {
            $model->created = time();
            $model->client_id = $id;
            $file = UploadedFile::getInstance($model, 'imageFile');
            if ($file && $file->tempName) {
                $model->imageFile = $file;
                if ($model->validate(['file'])) {
                    $dir = Yii::getAlias('@frontend/web/uploads/client_documents/');
                    $name = time();
                    $fileName = $name . '.' . $model->imageFile->extension;
                    $model->imageFile->saveAs($dir . $fileName);
                    $model->imageFile = $fileName;
                    $model->image = $fileName;
                }
            }
            if ($model->save()) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($this->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->guarantor_id = 0;
                $model->created = time();
                $file = UploadedFile::getInstance($model, 'imageFile');
                if ($file && $file->tempName) {
                    $model->imageFile = $file;
                    if ($model->validate(['file'])) {
                        $dir = Yii::getAlias('@frontend/web/uploads/client_documents/');
                        $name = time();
                        $fileName = $name . '.' . $model->imageFile->extension;
                        $model->imageFile->saveAs($dir . $fileName);
                        $model->imageFile = $fileName;
                        $model->image = $fileName;
                    }
                }
                if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionConnectCard($id)
    {
        if ($this->request->isAjax) {
            $model = ClientCards::findOne($id);
            $credits = Credit::findAll(['client_id' => $model->client_id, 'credit_status' => 2]);
            return $this->renderAjax('_form_connect_cards', ['model' => $model, 'credits' => $credits]);
        }
    }

    public function actionLinkCard()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (Yii::$app->request->isPost) {

                $post = Yii::$app->request->post();
                $card = ClientCards::findOne(['id' => $post['card_id']]);
                foreach ($post['attach'] as $key => $item) {
                    if ($item == 0) {
                        $res = \frontend\modules\api\controllers\AlgenixController::attachCard($card->client_id, $key, $card->id, false);
                        if ($res['success'] === false) throw new \Exception($res['data']);
                        $linked = CardCreditLink::findOne(['credit_id' => $key, 'card_id' => $card->id]);
                        if ($linked) $linked->delete();
                        continue;
                    }
                    $res = \frontend\modules\api\controllers\AlgenixController::attachCard($card->client_id, $key, $card->id, true);
                    if ($res['success'] === false) throw new \Exception($res['data']);
                    $link = new CardCreditLink();
                    $link->credit_id = $key;
                    $link->card_id = $card->id;
                    $link->created = time();
                    $link->save(false);
                }
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Карты успешно привязаны к договору');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo "<pre>";
            print_r($e);
            die();
        }

    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /*if (Yii::$app->user->identity->role != 0) {
            if (Yii::$app->language === 'ru') {
                Yii::$app->session->setFlash('warning', 'У вас нет доступа для изменений');

            } else {
                Yii::$app->session->setFlash('warning', 'Sizda o\'zgarishga imkoningiz yo\'q');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }*/
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->birthday = $_POST['Client']['birthday'];
            $file = UploadedFile::getInstance($model, 'imageFile');
            if ($file && $file->tempName) {
                $model->imageFile = $file;
                if ($model->validate(['file'])) {
                    $dir = Yii::getAlias('@frontend/web/uploads/client_documents/');
                    $name = time();
                    $fileName = $name . '.' . $model->imageFile->extension;
                    $model->imageFile->saveAs($dir . $fileName);
                    $model->imageFile = $fileName;
                    $model->image = $fileName;
                }
            }
            if ($model->save(false)) return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSendWarning($phone_id)
    {
        $model = ClientPhones::findOne(['id' => $phone_id]);
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($this->request->isPost) {
                if ($model->numb < 9) {
                    Yii::$app->session->setFlash('danger', 'Неверный формат номера');
                    return $this->redirect(Yii::$app->request->referrer);
                }
                $credit = \common\models\Credit::find()
                    ->where(['id' => $_POST['credit_id']])
                    ->one();
                $plans = CreditPlan::find()
                    ->where(['credit_id' => $credit->id])
                    ->andWhere(['<', 'created', time()])
                    ->andWhere(['pay_status' => [0, 4, 5, 6, 7]])
                    ->sum('pay_summa');
                $payments = Payments::find()
                    ->where(['credit_id' => $credit->id])
                    ->sum('amount');
                $ost = Yii::$app->formatter->asDecimal((int)$plans - (int)$payments, 0);
                $phone = $model->numb;
                // убираем всё кроме цифр
                $digits = preg_replace('/\D/', '', $phone);

                // если уже есть 998 — не трогаем
                if (!str_starts_with($digits, '998')) {
                    $digits = '998' . $digits;
                }
                $message = "Аssalomu alaykum. Hurmatli mijoz, Sizning № {$credit->id} -sonli shartnoma raqamingiz bo'yicha $ost so'm miqdorida  muddati o'tgan qarzdorlik mavjud. Iltimos qarzdorlikni to'lab berishingizni so'raymiz. Hurmat bilan, LUX Gilam savdo markazi.";
                Yii::$app->playmobile->sendSms("+$phone", $message);
                Yii::$app->session->setFlash('success', 'Сообщение отправлено');

                $tr->commit();
                return $this->redirect(Yii::$app->request->referrer);
            }
        } catch (\Exception $ex) {
            $tr->rollBack();
            Yii::$app->session->setFlash('danger', $ex->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    /**
     * Deletes an existing Client model.
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

    public function actionPhoneDelete($item, $view)
    {
        $model = ClientPhones::findOne($item);
        $model->delete();
        return $this->redirect(['view', 'id' => $view]);
    }

    public function actionFileDelete($id)
    {
        $model = \common\models\ClientFiles::findOne($id);
        unlink(Yii::getAlias('@frontend/web/uploads/client_documents/' . $model->image));
        if (Yii::$app->user->identity->role === 0) {
            Yii::$app->session->setFlash('success', 'Успешно удалено');
            $model->delete();
        } else {
            Yii::$app->session->setFlash('danger', 'Доступ отклонён');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionToBlacklist($id)
    {
        $model = $this->findModel($id);
        $model->is_blacklist = 1;
        $model->blacklist_user_id = Yii::$app->user->id;
        $model->blacklist_time = time();
        $model->save(false);
        Yii::$app->session->setFlash('warning', Yii::$app->params['labels_basket'][Yii::$app->language]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionFromBlacklist($id)
    {
        $model = $this->findModel($id);
        $model->is_blacklist = 0;
        $model->blacklist_user_id = Yii::$app->user->id;
        $model->blacklist_time = time();
        $model->save(false);
        // Yii::$app->session->setFlash('warning',Yii::$app->params['labels_basket'][Yii::$app->language]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
