<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$lang = Yii::$app->language;
?>
<div class="client-phone">
    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['/client/extra-file', 'id' => $user_id])]); ?>
    <div class="row align-items-center">
        <div class="col-md-8">
            <?= $form->field($model, 'imageFile')->fileInput(['required' => 'required'])->label(false) ?>
        </div>
        <div class="col-md-4" style="padding-bottom: 20px;"><br/>
            <?= Html::submitButton(Yii::$app->params['labels_create'][$lang], ['class' => 'btn btn-block btn-success']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>