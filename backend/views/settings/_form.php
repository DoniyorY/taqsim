<?php

use common\models\Company;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Settings $model */
/** @var yii\widgets\ActiveForm $form */

if (Yii::$app->controller->action->id == 'index') {
   $options = ['options' => ['enctype' => 'multipart/form-data'],'action' => ['create']];
}else{
   $options = ['options' => ['enctype' => 'multipart/form-data'],'action' => ['update','id'=>$model->id]];
}
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin($options); ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput() ?>
            </div>
            <div class="col-md-4">
               <?=$form->field($model, 'company_id')->dropDownList(ArrayHelper::map(Company::find()->all(),'id','name'),['prompt'=>'Выберите Филиал'])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'value')->textInput() ?>
            </div>
            <div class="form-group col-md-12">
                <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-block btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
