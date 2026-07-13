<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Вход';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

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
