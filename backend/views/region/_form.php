<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Region */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
?>

<div class="region-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' =>'Название'])->label(false) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?= Html::submitButton(Yii::$app->params['labels_save'][$lang], ['class' => 'btn btn-block btn-success']) ?>
            </div>
        </div>
    </div>




    <?php ActiveForm::end(); ?>

</div>
