<?php

use kartik\editors\Summernote;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'company_title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'company_director')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
           <?php
           echo $form->field($model, 'company_props')->widget(Summernote::class, [
              'options' => [
                 'rows' => 6,
              ],
              
              'language' => Yii::$app->language,
              
              'useKrajeePresets' => false,
              
              'pluginOptions' => [
                 'height' => 300,
                 
                 'toolbar' => [
                    ['history', ['undo', 'redo']],
                    
                    ['style', ['style']],
                    
                    ['font', [
                       'bold',
                       'italic',
                       'underline',
                       'strikethrough',
                       'clear'
                    ]],
                    
                    ['para', [
                       'ul',
                       'ol',
                       'paragraph'
                    ]],
                    
                    ['insert', [
                       'link',
                       'picture',
                       'video',
                       'table',
                       'hr'
                    ]],
                    
                    ['view', [
                       'fullscreen',
                       'codeview',
                       'help'
                    ]],
                 ],
              ],
           ]);
           
           ?>
        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-block btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
