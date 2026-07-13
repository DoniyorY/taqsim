<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Credit */
$lang=Yii::$app->language;
if ($lang == 'ru') {
    $method = [0 => 'Наличные', 1 => 'Карта'];
} else {
    $method = [0 => 'Накд', 1 => 'Карта'];
}
?>
<div class="credit-update">

    <h5><?= Html::encode('Настройки') ?></h5>

    <?php $form = \yii\widgets\ActiveForm::begin()?>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'self_price')->textInput(['type' => 'number', 'id' => 'credit_self_price', 'readonly' => 'readonly']);?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'percent')->textInput(['type' => 'number', 'id' => 'credit_percent', 'onchange'=> 'calcCredit()', 'required' => 'required']);?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'month_count')->textInput(['type' => 'number', 'id' => 'credit_month', 'onchange'=> 'calcCredit()', 'required' => 'required']);?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'prepaid_summa')->textInput(['type' => 'number', 'id' => 'credit_prepaid', 'onchange'=> 'calcCredit()', 'value' => 0, 'required'=>true]);?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'method_id')->radioList($method, ['value' => 0])?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'doc_total_price')->textInput(['id' => 'credit_total_price']);?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'doc_total_text')->textInput();?>
        </div>
        <div class="col-md-12">
            <?=Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block'])?>
        </div>
    </div>


    <?php \yii\widgets\ActiveForm::end();?>

</div>
