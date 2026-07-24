<?php

use common\models\CompanyPlanLimit;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $limitModel common\models\CompanyPlanLimit */
/* @var $contractLimitDataProvider yii\data\ActiveDataProvider */
/* @var $paymentLimitDataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'company_title',
                    'company_props:html',
                    'company_director',
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Добавить лимит</strong></div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($limitModel, 'type')->dropDownList(CompanyPlanLimit::typeLabels()[Yii::$app->language]) ?>
                    <?= $form->field($limitModel, 'limit')->textInput(['type' => 'number', 'min' => 0]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <h3><?= Html::encode(CompanyPlanLimit::typeLabels()[Yii::$app->language][CompanyPlanLimit::TYPE_CONTRACTS]) ?></h3>
            <?= GridView::widget([
                'dataProvider' => $contractLimitDataProvider,
                'summary' => false,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'limit',
                        'value' => function (CompanyPlanLimit $model) {
                            return Yii::$app->formatter->asDecimal($model->limit,0);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (CompanyPlanLimit $model) {
                            return $model->getStatusLabel();
                        },
                    ],
                    [
                        'attribute' => 'created',
                        'value' => function (CompanyPlanLimit $model) {
                            return date('d.m.Y H:i', $model->created);
                        },
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function (CompanyPlanLimit $model) {
                            return $model->user ? $model->user->username : $model->user_id;
                        },
                    ],
                ],
            ]) ?>

            <h3><?= Html::encode(CompanyPlanLimit::typeLabels()[Yii::$app->language][CompanyPlanLimit::TYPE_PAYMENTS]) ?></h3>
            <?= GridView::widget([
                'dataProvider' => $paymentLimitDataProvider,
                'summary' => false,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'limit',
                        'value' => function (CompanyPlanLimit $model) {
                            return Yii::$app->formatter->asDecimal($model->limit,0);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (CompanyPlanLimit $model) {
                            return $model->getStatusLabel();
                        },
                    ],
                    [
                        'attribute' => 'created',
                        'value' => function (CompanyPlanLimit $model) {
                            return date('d.m.Y H:i', $model->created);
                        },
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function (CompanyPlanLimit $model) {
                            return $model->user ? $model->user->username : $model->user_id;
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
