<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CreditType */
/* @var $form yii\widgets\ActiveForm */

$lang = Yii::$app->language;
?>

<div class="credit-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?=$form->field($model, 'type')->dropDownList(Yii::$app->params['credit_type'],['prompt'=>Yii::$app->params['label_credit_type'][$lang]])->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::$app->params['input_credit_type'][Yii::$app->language]])->label(false) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-block btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
