<?php

use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['payment_control'][$lang];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <tr>
                    <th class="table-secondary"><?php echo yii::$app->params['report_cash'][$lang] ?>:</th>
                    <td><?= Yii::$app->formatter->asDecimal($cash, 0) ?></td>
                    <th class="table-secondary"><?php echo yii::$app->params['report_card'][$lang] ?>:</th>
                    <td><?= Yii::$app->formatter->asDecimal($card, 0) ?></td>
                    <th class="table-secondary"><?php echo yii::$app->params['report_atmos'][$lang] ?>:</th>
                    <td><?= Yii::$app->formatter->asDecimal($atmos, 0) ?></td>
                    <th class="table-secondary">Algenix:</th>
                    <td><?= Yii::$app->formatter->asDecimal($algenix) ?></td>
                    <th class="table-secondary">MIB</th>
                    <td><?= Yii::$app->formatter->asDecimal($mib, 0) ?></td>
                    <th class="table-secondary"><?php echo yii::$app->params['itogo'][$lang] ?>:</th>
                    <td><?= Yii::$app->formatter->asDecimal($total, 0) ?></td>

                </tr>
            </table>
        </div>
    </div>

    <?php echo $this->render('_search', [
        'model' => $searchModel,
        'model_create' => $model_create,
    ]); ?>

    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'format' => 'html',
            //'value' =>'fullname',
            'value' => function ($data) {
                return '<a class="btn btn-primary btn-sm btn-block"
                         href="' . \yii\helpers\Url::to(['/credit-invoice/cheque', 'id' => $data->id]) . '">ЧЕК</a>';
            },
        ],
        [
            'header' => ($lang == 'ru') ? 'Дата плана' : 'План куни',
            'value' => function ($data) {
                if ($data->plan) {
                    return date('d.m.Y', $data->plan->created);

                } else {
                    return "Не задано!!!";
                }
            }
        ],
        [
            'attribute' => 'created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->created, "php:d.m.Y");
            },
            'contentOptions' => ['style' => 'width:90px;']
        ],
        [
            'attribute' => 'payment_type',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->payment_type == 0) {
                    return '<span class="badge badge-success" style="font-size: 14px; font-weight: 500;">' . Yii::$app->params['payment_type'][Yii::$app->language][0] . ' <i class="fa fa-arrow-down" aria-hidden="true"></i></span>';
                } elseif ($data->payment_type == 1) {
                    return '<span class="badge badge-danger" style="font-size: 14px; font-weight: 500;">' . Yii::$app->params['payment_type'][Yii::$app->language][1] . ' <i class="fa fa-arrow-up" aria-hidden="true"></i></span>';
                }
            },
            'filter' => Yii::$app->params['payment_type'][$lang],
            'contentOptions' => ['style' => 'width:150px']
        ],
        [
            'attribute' => 'company_id',
            'value' => function ($data) {
                if ($data->company) {
                    return $data->company->name;
                }
            },
            'filter' => ArrayHelper::map(\common\models\Company::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name')
        ],
        [
            'attribute' => 'user_id',
            'value' => function ($data) {
                if (isset($data->user->username)) {
                    return $data->user->username;
                } else {
                    return 'Удален!';
                }
            },
            'filter' => ArrayHelper::map(\common\models\User::find()->orderBy(['username' => SORT_ASC])->all(), 'id', 'username')
        ],
        [
            'attribute' => 'amount',
            'value' => function ($data) {
                return Yii::$app->formatter->asDecimal($data->amount, 0);
            },
            'footer' => \common\models\Payments::getTotalCount($dataProvider->models, 'amount'),
        ],
        [
            'attribute' => 'method_id',
            'value' => function ($data) {
                return Yii::$app->params['method'][Yii::$app->language][$data->method_id];
            },
            'filter' => [0 => 'Наличные', 1 => 'Карта', 2 => Yii::$app->params['method'][Yii::$app->language][2], 3 => Yii::$app->params['method'][Yii::$app->language][3], 4 => Yii::$app->params['method'][Yii::$app->language][4]],
        ],
        [
            'attribute' => 'pay_type',
            'value' => function ($data) {
                return Yii::$app->params['pay_type'][Yii::$app->language][$data->pay_type];
            },
            'filter' => Yii::$app->params['pay_type'][Yii::$app->language],
        ],
        [
            'attribute' => 'content',
            'contentOptions' => ['style' => 'font-size: 0.9em']
        ],
        //'credit_plan_id',
        //'credit_id',
        [
            'attribute' => 'credit_type_id',
            'value' => function ($data) {
                if ($data->credit_type_id == null) {
                    return 'Не задано';
                } else {
                    return $data->type->name;
                }
            },
            'filter' => ArrayHelper::map(\common\models\CreditType::find()->all(), 'id', 'name'),
        ],

    ];
    if (Yii::$app->user->identity->role === 0) {
        $gridColumns[] = [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template' => '{delete}'
        ];
    }
    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => $this->title . '-' . date('d.m.Y')],
        ],
        'filename' => $this->title . '-' . date('d.m.Y')
    ]);
    // You can choose to render your own GridView separately
    \yii\widgets\Pjax::begin();
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm', 'style' => 'font-size:0.9em;text-align:center;'],
        'showFooter' => true,
        'columns' => $gridColumns
    ]);
    \yii\widgets\Pjax::end();
    ?>
</div>
