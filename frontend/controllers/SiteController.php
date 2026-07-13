<?php

namespace frontend\controllers;

use common\models\CreditInvoice;
use common\models\CreditItem;
use common\models\CreditPlan;
use common\models\CreditSign;
use common\models\Guarantor;
use common\models\Payments;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login', 'index', 'check'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'check'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all Client models.
     *
     * @return string
     */
    public function actionCheck($n)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = \common\models\Client::find()->where(['passport_numb' => trim($n)])->one();

        if ($model == true) {
            return [
                'id' => $model->id,
                'fullname' => $model->fullname,
            ];
        }
        if ($model == false) {
            return false;
        }
    }


    public function actionTestPlaymobile()
    {
        \Yii::$app->playmobile->sendSms('+998995993603', 'Taqsimsavdo Test sms');
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxRequest()
    {
        $this->response->format = Response::FORMAT_JSON;
        $dayStart = mktime(0, 0, 0);
        $dayEnd = $dayStart + 86400;
        $total_cash = Payments::find()->where(['>', 'created', 1601510400])->andWhere(['method_id' => 0])->sum('amount');
        $total_card = Payments::find()->where(['>', 'created', 1601510400])->andWhere(['method_id' => 1])->sum('amount');
        $total_atmos = Payments::find()->where(['>', 'created', 1601510400])->andWhere(['method_id' => 2])->sum('amount');

        $today_cash = Payments::find()->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['method_id' => 0])->sum('amount');
        $today_card = Payments::find()->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['method_id' => 1])->sum('amount');
        $today_atmos = Payments::find()->where(['between', 'created', $dayStart, $dayEnd])->andWhere(['method_id' => 2])->sum('amount');
        return [
            'total_cash' => $total_cash,
            'total_card' => $total_card,
            'today_cash' => $today_cash,
            'today_card' => $today_card,
            'total_atmos' => $total_atmos,
            'today_atmos' => $today_atmos ? $today_atmos : 0,
        ];

    }

    public function actionLogs()
    {
        $algenix = \common\models\AlgenixLogs::find()
            ->select(['id', 'created', 'amount', 'content', 'ip', 'action'])
            ->where(['is not', 'transaction_id', NULL])
            ->andWhere(['between', 'created', mktime(0, 0, 0, 2, 1, 2026), mktime(23, 59, 59, 2, 13, 2026)])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $arr = [];
        $algenix_total=0;
        foreach ($algenix as $key => $item) {
            if (preg_match('/№(\d+)/u', $item->content, $m)) {
                $algenix_total += $item->amount;
                $credit_id = $m[1];
                $payment = \common\models\Payments::find()
                    ->where(['credit_id' => $credit_id])
                    ->andWhere(['method_id' => 3])
                    ->andWhere(['between', 'created', mktime(0, 0, 0, 2, 1, 2026), mktime(23, 59, 59, 2, 13, 2026)])
                    ->all();
                $arr[] = [
                    'algenix_id' => $item->id,
                    'algenix_content' => $item->content ?? null,
                    'algenix_created' => date('d.m.Y H:i', $item->created),
                    'algenix_amount' => Yii::$app->formatter->asDecimal($item->amount,0),
                    'payment' => null,
                ];
                foreach ($payment as $value) {
                    $arr[$key]['payment'][] = [
                        'payment_id' => $value->id ?? null,
                        'payment_created' => date('d.m.Y H:i', $value->created),
                        'payment_content' => $value->content ?? null,
                        'payment_method' => Yii::$app->params['method']['ru'][$value->method_id] ?? null,
                        'payment_amount'=>$value->amount,
                    ];
                }
            }
        }
        $null_payment = [];
        foreach ($arr as $item) {
            if (is_null($item['payment'])) {
                $null_payment[] = [
                    'id' => $item['algenix_id'],
                    'content' => $item['algenix_content'],
                    'amount' => $item['algenix_amount']
                ];
            } else {
                continue;
            }
        }

        return $this->asJson([$arr, $null_payment, Yii::$app->formatter->asDecimal($algenix_total,0)]);

    }

    public function actionFormat()
    {
        return $this->render('taktak_format');
    }

    public function actionStep1()
    {
        return $this->render('taktak_1');
    }

    public function actionStep2()
    {
        return $this->render('taktak_2');
    }

    public function actionStep3()
    {
        return $this->render('taktak_3');
    }

    public function actionStep4()
    {
        return $this->render('taktak_4');
    }

    public function actionDeleteItems()
    {
        /*\common\models\Client::deleteAll(['between', 'created', 1685577600, time()]);
        \common\models\ClientFiles::deleteAll(['between', 'created', 1685577600, time()]);
        \common\models\ClientPhones::deleteAll(['between', 'created', 1685577600, time()]);
        $credit = \common\models\Credit::findAll(['between', 'created', 1685577600, time()]);
        foreach ($credit as $item) {
            CreditItem::deleteAll(['credit_id' => $item->id]);
            CreditSign::deleteAll(['credit_id' => $item->id]);
        }
        CreditInvoice::deleteAll(['between', 'created', 1685577600, time()]);
        CreditPlan::deleteAll(['between', 'created', 1685577600, time()]);
        Guarantor::deleteAll(['between', 'created', 1685577600, time()]);
        Payments::deleteAll(['between', 'created', 1685577600, time()]);

        Yii::$app->session->setFlash('success', 'Успешно удалено');
        return $this->redirect(Yii::$app->homeUrl);
        \common\models\Credit::deleteAll(['between', 'created', 1685577600, time()]);*/
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
