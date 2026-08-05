<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\SecurityEventSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="security-event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'event_type') ?>

    <?= $form->field($model, 'rule') ?>

    <?php // echo $form->field($model, 'severity') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'method') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_agent') ?>

    <?php // echo $form->field($model, 'matched_value') ?>

    <?php // echo $form->field($model, 'payload') ?>

    <?php // echo $form->field($model, 'payload_hash') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
