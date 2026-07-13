<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Payments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'payment_type')->textInput() ?>

    <?= $form->field($model, 'method_id')->textInput() ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'company_id')->textInput() ?>

    <?= $form->field($model, 'content')->textInput() ?>

    <?= $form->field($model, 'credit_plan_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'credit_id')->textInput() ?>

    <?= $form->field($model, 'credit_type_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
