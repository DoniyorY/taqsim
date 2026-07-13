<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
$form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['/payments/lawyer-income'])]); ?>
    <div class="row">
        <div class="col-md-2"> <?= $form->field($model_create, 'credit_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\common\models\Credit::find()->where(['credit_status' => 2])->all(), 'id', 'label'),
                'language' => $lang,
                'options' => ['placeholder' => '. . .'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'size' => Select2::SMALL,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['/payments/jsonc']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { 
                                    return "Договор №" + city.id;
                             }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],

            ]); ?></div>
        <div class="col-md-2"> <?= $form->field($model_create, 'method_id')->dropDownList(Yii::$app->params['method'][$lang]) ?></div>
        <div class="col-md-2"> <?= $form->field($model_create, 'company_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name')) ?></div>
        <div class="col-md-2"> <?= $form->field($model_create, 'amount')->textInput() ?> </div>
        <div class="col-md-2"> <?= $form->field($model_create, 'content')->textInput() ?></div>
        <div class="col-md-2">
            <br/><?= Html::submitButton(Yii::$app->params['labels_save'][$lang], ['class' => 'btn btn-success btn-block']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>