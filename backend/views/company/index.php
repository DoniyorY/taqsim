<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['labels_company'][$lang];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">
    <div class="row">
        <div class="col-md-8">
            <h1 style="margin: 0;"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 text-right ">
            <p>
                <?= Html::a(Yii::$app->params['labels_create'][$lang], ['create'], ['class' => 'btn btn-block btn-success']) ?>
            </p>
        </div>
    </div>


    <?php

    $gridColumns = [

        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        'name',
        'company_title',
        'company_props:html',
        'company_director',
        [
            'attribute' => 'status',
            'value' => function ($data) {
                if ($data->status === 0) {
                    return Html::a(Yii::$app->params['status'][Yii::$app->language][$data->status], ['status', 'id' => $data->id, 'status' => 1], ['class' => 'btn btn-success btn-sm w-100']);
                } else {
                    return Html::a(Yii::$app->params['status'][Yii::$app->language][$data->status], ['status', 'id' => $data->id, 'status' => 0], ['class' => 'btn btn-danger btn-sm w-100']);
                }
            },
            'format'=>'raw',
        ],
        ['class' => 'yii\grid\ActionColumn'],

    ];

    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => 'Magazinlar: ' . date('d-m-Y')],
        ],
        'filename' => Yii::$app->params['labels_company'][$lang] . date('d-m-Y')
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'rowOptions' => function ($data) {
            // if($data->prior==0) { return ['class'=>'table-danger'];  }
        },
    ]);
    ?>

</div>
