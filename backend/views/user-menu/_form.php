<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-menu-form">

    <?php
    if (Yii::$app->controller->action->id == 'update'){
        $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['/user-menu/update', 'id' => $model->id])]);
    }else{
        $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['/user-menu/create'])]);
    }
     ?>
    <div class="row">
        <div class="col-md-2">
            <?=$form->field($model, 'category')->dropDownList(Yii::$app->params['link_category'][Yii::$app->language], ['prompt' => ''])?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'content_ru')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'content_uz')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'prior')->textInput() ?>
        </div>
        <div class="form-group col-md-2 mt-4">
            <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-success btn-block']) ?>
        </div>
    </div>






    <?php ActiveForm::end(); ?>

</div>
