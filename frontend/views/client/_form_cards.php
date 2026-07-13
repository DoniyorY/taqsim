<?php
use yii\widgets\ActiveForm;

/**
 * @var common\models\ClientCards $model
 * @var ActiveForm $form
 * @var common\models\Client $client_id
 */
?>
<?php $form= ActiveForm::begin(['action'=>\yii\helpers\Url::to(['create-card']),'method'=>'post']);?>
<?=$form->field($model,'client_id')->textInput(['value'=>$client_id,'hidden'=>true])->label(false)?>
<div class="row">
    <div class="col-md-12">
            <?=$form->field($model,'card_name')->textInput()?>
    </div>
    <div class="col-md-8">
        <?=$form->field($model,'card_number')->widget(\yii\widgets\MaskedInput::class,[
                'mask'=>'9999 9999 9999 9999',

            ])?>
    </div>
    <div class="col-md-4">
        <?=$form->field($model,'card_date')->widget(\yii\widgets\MaskedInput::class,[
                'mask'=>'99/99'
            ])?>
    </div>
    <div class="col-md-12 mt-1">
        <?=\yii\helpers\Html::submitButton('Сохранить',['class'=>'btn btn-success w-100'])?>
    </div>
</div>
<?php ActiveForm::end()?>
