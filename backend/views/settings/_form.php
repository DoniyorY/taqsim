<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Settings $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'value')->textInput() ?>
            </div>
            <div class="form-group col-md-12">
                <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-block btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
