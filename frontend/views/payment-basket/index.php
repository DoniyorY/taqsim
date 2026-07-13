<?php

use common\models\PaymentBasket;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var common\models\search\PaymentBasketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$lang = Yii::$app->language;
$this->title = 'Payment Baskets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-basket-index">

    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
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
            'attribute' => 'payment_created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->payment_created, "php:d.m.Y");
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
            'filter' => [0 => 'Наличные', 1 => 'Карта', 2 => Yii::$app->params['method'][Yii::$app->language][2], 3 => Yii::$app->params['method'][Yii::$app->language][3]],
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
        [
            'attribute' => 'deleted_user_id',
            'value' => function ($data) {
                return $data->deletedUser->username;
            }
        ],
        [
            'attribute' => 'deleted_time',
            'value' => function ($data) {
                return date('d.m.Y H:i:s', $data->deleted_time);
            }
        ],
        [
            'value' => function ($data) {
                $lang = Yii::$app->language;
                return Html::a(($lang == 'ru') ? 'Восстановить' : 'Кайтариш', ['return-payment', 'id' => $data->id], ['class' => 'btn btn-success btn-sm']);
            },
            'format' => 'raw',
        ],
    ];
    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => $this->title . '-' . date('d.m.Y')],
        ],
        'filename' => $this->title . '-' . date('d.m.Y')
    ]);
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm', 'style' => 'font-size:0.9em;text-align:center;'],
        'showFooter' => true,
        'columns' => $gridColumns,
        'rowOptions'=>['class'=>'table-danger']
    ]);
    ?>

</div>
