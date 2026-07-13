<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Вход';
?>

<div class="site-login container-fluid own-style mh-100" style="margin-top:100px;">
    <div class="row">
        <div class="col-md-6">
            <img src="<?=Yii::$app->request->baseUrl. '/uploads/lux_bg.jpeg'?>" style="width: 100%; object-fit: cover; height: 500px;" alt="">
        </div>
        <div class="col-md-5 align-self-center">
            <div class="text-center">
                <img src="<?=Yii::$app->request->baseUrl. '/uploads/logo.png'?>" style="height: 60px;margin-top:10px;" alt="">
            </div>
            <hr/>
            <h4 class="text-center"><?= Html::encode($this->title) ?></h4>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логин') ?>

            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

            <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>
            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
