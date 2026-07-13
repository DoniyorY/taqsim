<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<?php $form = ActiveForm::begin(['method' => 'get']) ?>
<div class="row">
    <div class="col-md-5">
        <label for="date_begin">
            <?= Yii::$app->params['labels_doc_date_start'][Yii::$app->language] ?>
        </label>
        <?= Html::input('date', 'date_begin', '', ['class' => 'form-control', 'id' => 'date_begin']) ?>
    </div>
    <div class="col-md-5">
        <label for="date_end">
            <?= Yii::$app->params['labels_doc_date_end'][Yii::$app->language] ?>
        </label>
        <?= Html::input('date', 'date_end', '', ['class' => 'form-control', 'id' => 'date_end']) ?>
    </div>
    <div class="col-md-1">
        <?= Html::submitButton(Yii::$app->params['header_search_button'][Yii::$app->language], ['class' => 'btn btn-primary btn-block mt-4']) ?>
    </div>
    <div class="col-md-1">
        <?= Html::a('Сбросить', ['/report/index'], ['class' => 'btn btn-warning btn-block mt-4 text-white']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
