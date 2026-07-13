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
        'phone',
        'passport_numb',
        [
            'attribute' => 'credit_limit',
            'format' => 'raw',
            'value' => function ($data) {
                if (Yii::$app->controller->action->id == 'guar') {
                    return Yii::$app->formatter->asDecimal($data->credit_limit, 0);
                }
            },
            'contentOptions' => ['class' => 'table-warning font-weight-bold text-center']
        ],
        [
            'header' => Yii::$app->params['payed_by_credit'][Yii::$app->language],
            'format' => 'raw',
            'value' => function ($data) {
                $res=$data->credit_limit-$data->summaGuar($data->id);
                return Yii::$app->formatter->asDecimal($res, 0);
            },
            'contentOptions' => ['class' => 'table-success font-weight-bold text-center']
        ],
        [
            'header' =>Yii::$app->params['unpayed_by_credit'][Yii::$app->language],
            'format' => 'raw',
            'value' => function ($data) {
                $res=$data->summaGuar($data->id);
                return Yii::$app->formatter->asDecimal($res, 0);
            },
            'contentOptions' => ['class' => 'table-danger font-weight-bold text-center']
        ],



        [
            'attribute' => 'created',
            'value' => function ($data) {
                return date('d.m.Y H:i:s',$data->created);
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
            ExportMenu::FORMAT_EXCEL => ['filename' => $title.'-'.date('d-m-Y')],
        ],
        'filename' => $title.'-'.date('d-m-Y')
    ]);

    // You can choose to render your own GridView separately
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns
    ]);
    ?>


</div>
