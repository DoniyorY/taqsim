<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ClientPhones */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
?>
<div class="client-phone">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'content')->textInput([]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'numb')->textInput(['type' => 'number', 'value' => '998']) ?>
        </div>
        <div class="col-md-4"><br/>
            <?= Html::submitButton(Yii::$app->params['labels_create'][$lang],['class' => 'btn btn-block btn-success mt-2']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

