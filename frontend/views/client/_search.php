<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created') ?>

    <?= $form->field($model, 'fullname') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'passport_numb') ?>

    <?php // echo $form->field($model, 'passport_whose') ?>

    <?php // echo $form->field($model, 'passport_enddate') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'image') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
