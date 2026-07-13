<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        'fullname',
        'passport_pinfl',
        [
            'attribute' => 'phone',
            'format' => 'raw'
        ],
        'passport_numb',
        [
            'attribute' => 'created',
            'value' => function ($data) {
                return date('d.m.Y H:i:s', $data->created);
            }
        ],
        [
            'attribute' => 'birthday',
            'value' => function ($data) {
                return $data->birthday;
            }
        ],
        //'passport_whose',
        //'passport_enddate',
        //'image',
        [
            'attribute' => 'credit_score',
            'value' => function ($data) {
                return Html::button(\Yii::$app->params['client_credit_score'][Yii::$app->language][$data->credit_score], ['class' => Yii::$app->params['client_credit_score_class'][$data->credit_score]]);
            },
            'format' => 'raw',
            'filter' => Yii::$app->params['client_credit_score'][Yii::$app->language]
        ],
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template' => '{view}'
        ],
    ];

    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => $title . '-' . date('d-m-Y')],
        ],
        'filename' => $title . '-' . date('d-m-Y')
    ]);

    // You can choose to render your own GridView separately
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns
    ]);
    ?>


</div>
