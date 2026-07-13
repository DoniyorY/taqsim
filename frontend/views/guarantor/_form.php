<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Guarantor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="guarantor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_numb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_whose')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_enddate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'credit_limit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
