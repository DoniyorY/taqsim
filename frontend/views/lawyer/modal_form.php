<?php
$lang = Yii::$app->language;
\frontend\assets\AppAsset::register($this)
?>

<div class="row">
    <?php $form = \yii\widgets\ActiveForm::begin()?>
    <div class="col-md-12">
        <div class="form-group">
            <?=\yii\helpers\Html::input('date', 'date', '', ['class' => 'from-control'])?>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
