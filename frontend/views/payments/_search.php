<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PaymentsSearch */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
?>

<divc class="p-3">
    <div class="row">
        <div class="col-md-12">
            <nav class="mb-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab"
                            data-toggle="tab" data-target="#nav-home" type="button" role="tab"
                            aria-controls="nav-home"
                            aria-selected="true"><?= Yii::$app->params['header_search_button'][$lang] ?></button>
                    <?php if (Yii::$app->user->identity->role == 0): ?>
                        <button class="nav-link" id="nav-home1-tab"
                                data-toggle="tab" data-target="#nav-home1" type="button" role="tab"
                                aria-controls="nav-home1"
                                aria-selected="false"><?= Yii::$app->params['payments_expense'][$lang] ?></button>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->identity->role == 3 or Yii::$app->user->identity->username == 0): ?>
                        <button class="nav-link" style="display: none" id="nav-home1-tab"
                                data-toggle="tab" data-target="#nav-home2" type="button" role="tab"
                                aria-controls="nav-home2"
                                aria-selected="false"><?= Yii::$app->params['lawyer_income'][$lang] ?></button>
                    <?php endif; ?>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($model, 'date_begin')->textInput(['type' => 'date']) ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($model, 'date_end')->textInput(['type' => 'date']) ?>
                        </div>
                        <div class="col-md-2">

                            <?= $form->field($model, 'company_id')
                                ->dropDownList(ArrayHelper::map(\common\models\Company::find()->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
                                    ['prompt' => '']) ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($model, 'user_id')
                                ->dropDownList(ArrayHelper::map(\common\models\User::find()->where(['status' => 10])->orderBy(['username'=>SORT_ASC])->asArray()->all(), 'id', 'username'),
                                    ['prompt' => '']) ?>
                        </div>
                        <div class="col-md-2">
                            <?=$form->field($model,'credit_type_id')->dropDownList(ArrayHelper::map(\common\models\CreditType::find()->all(),'id','name'),['prompt'=>'Выберите тип кредита'])?>
                        </div>
                        <div class="col-md-1 m-0 p-0"><br/>
                            <?= Html::submitButton(Yii::$app->params['header_search_button'][Yii::$app->language], ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                        <div class="col-md-1"><br/>
                            <?= Html::a(Yii::$app->params['labels_reset_button'][$lang], ['index'], ['class' => 'btn btn-info btn-block']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="tab-pane fade" id="nav-home1" role="tabpanel" aria-labelledby="nav-home1-tab">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-3"> <?= $form->field($model_create, 'method_id')->dropDownList(Yii::$app->params['method'][$lang]) ?></div>
                        <div class="col-md-2"
                             style="display:none;"> <?= $form->field($model_create, 'company_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name')) ?></div>
                        <div class="col-md-3"> <?= $form->field($model_create, 'amount')->textInput() ?> </div>
                        <div class="col-md-4"> <?= $form->field($model_create, 'content')->textInput() ?></div>
                        <div class="col-md-2">
                            <br/><?= Html::submitButton(Yii::$app->params['payments_spend_button'][$lang], ['class' => 'btn btn-success btn-block']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="tab-pane fade" id="nav-home2" role="tabpanel" aria-labelledby="nav-home2-tab">
                    <?php /*=$this->render('_lawyer_income',['lang'=>$lang,'model_create'=>$model_create])*/ ?>
                </div>
            </div>
        </div>
    </div>
</divc>