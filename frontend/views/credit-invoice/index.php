<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['credits_invoice'][$lang];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $gridColumns=[
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'attribute' => 'credit_id',
            'value' => function ($data) {
                return $data->credit_id . ' - ' . $data->credit->doc_date_start;
            }
        ],
        [
            'attribute' => 'created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->created, "php:d.m.Y");
            }
        ],
        //'status',

        [
            'attribute' => 'user_id',
            'value' => function ($data) {
                if (isset($data)){
                    return $data->user->username;
                }else{
                    return 'Не задано';
                }
            }
        ],
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template'=> '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    $btn = Html::a('<i class="fa fa-eye"></i>', ['view1', 'id' => $model->credit_id], [
                        'class' => 'btn btn-primary btn-sm',
                    ]);
                    return $btn;
                },
            ]

        ],
    ];

    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => $this->title.'-'.date('d-m-Y')],
        ],
        'filename' => $this->title.'-'.date('d-m-Y')
    ]);

    // You can choose to render your own GridView separately
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm','style'=>'font-size:0.9em;text-align:center;'],
        'columns' => $gridColumns
    ]);
    ?>

</div>
