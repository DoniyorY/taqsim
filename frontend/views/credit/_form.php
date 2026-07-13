<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Credit */
/* @var $form yii\widgets\ActiveForm */
$day = date('d');
$month = date('m');
$next_month = date('m') + 1;
$year = date('Y');
$next_year = date('Y') + 1;
//$total = $day . '.' . $month . '.' . $next_year;
$total = "$next_year-$month-$day";
$pay_next = $day . '.' . $next_month . '.' . $year;
?>

<div class="credit-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_date_start')->textInput(['type'=>'date','maxlength' => true, 'value' => ($model->isNewRecord)?date('Y-m-d'):Yii::$app->formatter->asDate($model->doc_date_start,"php:Y-m-d")]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'doc_date_end')->textInput(['type'=>'date', 'maxlength' => true, 'value' => ($model->isNewRecord)?$total:Yii::$app->formatter->asDate($model->doc_date_end,"php:Y-m-d")]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'pay_day')->textInput(
                [
                    'maxlength' => 2,
                    'min' => '1',
                    'max' => '31',
                    'type' => 'number',
                    'value' => (!$model->isNewRecord) ? Yii::$app->formatter->asDate($model->pay_day,'d') : '',
                ]
            ) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'credit_type_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\CreditType::find()->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => '. . . '],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'guarantor_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\Client::find()->where(['client_type' => 1])->all(), 'id', 'info'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите гаранта'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\Company::find()->where(['status' => 0])->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите магазин'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'region_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\Region::find()->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите регион'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'content')->textarea(['rows' => 2]) ?>
        </div>
        <div class="col-md-12 mb-2">
            <div class="card">
                <h5 class="card-header">Информация о залоге</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'guar_name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'guar_type')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'guar_count')->textInput() ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'guar_summa')->textInput() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header"><?= Html::encode('Свидетель продавец') ?></h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_seller_fullname')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_seller_address')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_seller_phone')->textInput() ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_seller_passport')->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => 'AA 9999999',
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header"><?= Html::encode('Свидетель покупатель') ?></h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_customer_fullname')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_customer_address')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_customer_phone')->textInput() ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'witness_customer_passport')->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => 'AA 9999999',
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group col-md-12 mt-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-block btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
