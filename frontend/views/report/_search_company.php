<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<?php $form = ActiveForm::begin([ 'method' => 'get','action' => 'company']) ?>
<div class="row">
    <div class="col-md-2">
        <label for="date_begin">
            <?= Yii::$app->params['labels_doc_date_start'][Yii::$app->language] ?>
        </label>
        <?= Html::input('date', 'date_begin', '', ['class' => 'form-control', 'id' => 'date_begin']) ?>
    </div>
    <div class="col-md-2">
        <label for="date_end">
            <?= Yii::$app->params['labels_doc_date_end'][Yii::$app->language] ?>
        </label>
        <?= Html::input('date', 'date_end', '', ['class' => 'form-control', 'id' => 'date_end']) ?>
    </div>
    <div class="col-md-3">
        <label for="company"><?=Yii::$app->params['labels_company'][Yii::$app->language]?></label>
        <?= Html::dropDownList('company_id', '', \yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name'), [ 'class' => 'form-control', 'id' => 'company']) ?>
    </div>
    <div class="col-md-3">
        <label for="user"><?=Yii::$app->params['labels_user'][Yii::$app->language]?></label>
        <?= Html::dropDownList('user_id', '', \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username'), ['prompt' => '', 'class' => 'form-control', 'id' => 'user']) ?>
    </div>
    <div class="col-md-1">
        <?= Html::submitButton(Yii::$app->params['header_search_button'][Yii::$app->language], ['class' => 'btn btn-primary btn-block mt-4']) ?>
    </div>
    <div class="col-md-1">
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-warning btn-block mt-4 text-white']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
