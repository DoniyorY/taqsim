<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'required'=>true])->label('Логин') ?>

            <?= $form->field($model, 'email')->label('Эл.почта') ?>

            <?= $form->field($model, 'password')->passwordInput(['required'=>true])->label('Пароль') ?>
            <hr/>
            <?=$form->field($model, 'role')->dropDownList(Yii::$app->params['user_roles'][Yii::$app->language], ['value'=> 2])->label('Роль')?>
            <div class="form-group">
                <?= Html::submitButton('Пройти регистрацию',
                    ['class' => 'btn btn-primary btn-block',
                        'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>
