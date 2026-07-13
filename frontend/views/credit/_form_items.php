<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\CreditItems */
/* @var $credit common\models\Credit */
?>

<div class="credit-items">
    <?php $form = ActiveForm::begin(['action' => Url::to(['/credit/create-item', 'credit' => $credit->id])])?>
    <div class="row">
        <div class="col-md-7">
            <?= $form->field($model, 'title')->textInput()?>
        </div>
        <div class="col-md-2">
            <?=$form->field($model, 'count')->textInput(['type' => 'number'])?>
        </div>
        <div class="col-md-2">
            <?=$form->field($model, 'summa')->textInput(['type' => 'number'])?>
        </div>
        <div class="col-md-1" style="padding-top: 30px;">
            <?=Html::submitButton('<i class="fa fa-plus" aria-hidden="true"></i>', ['class' => 'btn btn-success'])?>
        </div>
    </div>


    <?php ActiveForm::end()?>
</div>
