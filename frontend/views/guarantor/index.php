<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\GuarantorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Guarantors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="guarantor-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <p>
                <?= Html::a('Create Guarantor', ['create'], ['class' => 'btn btn-block btn-success']) ?>
            </p>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'fullname',
            'passport_numb',
            'birthday',
            'address',
            [
                'attribute' => 'created',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data->created);
                }
            ],
            //'passport_whose',
            //'passport_enddate',
            //'status',
            'credit_limit',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
