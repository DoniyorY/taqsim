<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$lang = Yii::$app->language;
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-index">

    <div class="row">
        <div class="col-md-2">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-10">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <?php
    $gridColumns = [
        [
            'attribute' => 'id',
            'format' => 'html',
            'value' => function ($data) {
                $end = $data->doc_date_end; //Yii::$app->formatter->asDate($data->doc_date_end, "php:d.m.Y");
                return '№ ' . $data->id;
            },
            'contentOptions' => ['style' => 'width:70px'],
        ],
        [
            'attribute' => 'client_id',
            'value' => function ($data) {
                if ($data->client) {
                    return $data->client->fullname;

                }
            },
        ],
        [
            'attribute' => 'client_phone',
            'header' => Yii::$app->params['labels_phone'][$lang],
            'value' => function ($data) {
                if ($data->client) return $data->client->phone;
            },
        ],
        [
            'attribute' => 'user_id',
            'value' => function ($data) {
                return (isset($data->user->username)) ? $data->user->username : '-';
            },
            'filter' => ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'username'),
        ],
        [
            'attribute' => 'credit_type_id',
            'value' => function ($data) {
                return $data->creditType->name;
            },
            'filter' => ArrayHelper::map(\common\models\CreditType::find()->asArray()->all(), 'id', 'name'),
        ],
        //'guarantor_id',
        //'pay_day',
        [
            'attribute' => 'company_id',
            'value' => function ($data) {
                if (isset($data)) {
                    return $data->company->name;
                } else {
                    return 'Не задано';
                }
            },
            'filter' => ArrayHelper::map(\common\models\Company::find()->asArray()->all(), 'id', 'name'),
        ],

        [
            'attribute' => 'doc_total_price',
            'value' => function ($data) {
                return Yii::$app->formatter->asDecimal($data->doc_total_price, 0);
            },
            'contentOptions' => ['style' => 'width:100px'],
            'format' => 'raw'
        ],
        [
            'header' => Yii::$app->params['payed_by_credit'][yii::$app->language],
            'value' => function ($data) {
                $payed = $data->getInfo($data->id);

                return Yii::$app->formatter->asDecimal(($payed), 0);
            },
            'contentOptions' => ['style' => 'width:100px; color:green;'],
            'format' => 'raw'
        ],
        [
            'header' => Yii::$app->params['unpayed_by_credit'][yii::$app->language],
            'format' => 'html',
            'value' => function ($data) {
                $payed = $data->getInfo($data->id);
                $un_payed = $data->doc_total_price - $payed;
                return yii::$app->formatter->asDecimal($un_payed, 0);
            },
            'contentOptions' => ['style' => 'width:100px; color:red;']
        ],

        [
            'attribute' => 'percent',
            'contentOptions' => ['style' => 'width: 70px']
        ],
        [
            'attribute' => 'month_count',
            'contentOptions' => ['style' => 'width: 50px']
        ],

        //'doc_total_text',
        [
            'attribute' => 'rejected_user_id',
            'value' => function ($data) {
                return $data->rejectedUser->username;
            }
        ],
        [
            'attribute' => 'rejected_time',
            'value' => function ($data) {
                return date('d.m.Y', $data->rejected_time);
            }
        ],
        'attribute' => 'rejected_reason',
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template' => '{view}'
        ],
        //'region_id',
        //'content:ntext',
        //'guar_name',
        //'guar_type',
        //'guar_count',
        //'guar_summa',
        //'witness_seller_fullname',
        //'witness_seller_address',
        //'witness_seller_phone',
        //'witness_seller_passport',
        //'witness_customer_fullname',
        //'witness_customer_address',
        //'witness_customer_phone',
        //'witness_customer_passport',
        //'self_price',
        //'percent',
        //'prepaid_summa',
        //'method_id',
        //'',
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
    \yii\widgets\Pjax::begin(['timeout' => 5000]);
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm', 'style' => 'font-size:0.9em;text-align:center;'],
        'rowOptions' => function ($model) {
            return ['class' => Yii::$app->params['credit_status_table'][$model->credit_status]];
        },
        'columns' => $gridColumns
    ]);
    \yii\widgets\Pjax::end();
    ?>


</div>
