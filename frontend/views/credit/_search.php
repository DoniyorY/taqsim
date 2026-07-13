<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; // or kartik\select2\Select2
use yii\helpers\ArrayHelper;

use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\search\CreditSearch */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
?>

<div class="credit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-6">
           <div class="form-group">
              <label for="begin_date"><?=Yii::$app->params['labels_doc_date_start'][$lang]?></label>
              <?=Html::input('date','Period[begin_date]','',['class'=>'form-control'])?>
           </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date"><?=Yii::$app->params['labels_doc_date_end'][$lang]?></label>
               <?=Html::input('date','Period[end_date]','',['class'=>'form-control'])?>
            </div>
        </div>
        <div class="col-md-4">
            <?php
            echo $form->field($model, 'client_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'size' => Select2::SMALL,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['client/jsonc']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { 
                                return city.text + "  -  " + city.phone;
                             }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],

            ]);
            ?>


        </div>
        <div class="col-md-4">

            <?php
            echo $form->field($model, 'guarantor_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'size' => Select2::SMALL,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['client/jsong']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { 
                                return city.text + "  -  " + city.credit_limit;
                             }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],

            ]);
            ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'credit_type_id')
                ->dropDownList(ArrayHelper::map(\common\models\CreditType::find()->asArray()->all(), 'id', 'name'),['prompt'=>'']) ?>
        </div>
        <div class="col-md-2"><br/>
            <?= Html::submitButton(Yii::$app->params['header_search_button'][Yii::$app->language], ['class' => 'btn btn-primary w-100']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
