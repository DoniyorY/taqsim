<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['credits_plan_today'][$lang];
$this->params['breadcrumbs'][] = $this->title;

$total_today_summa = 0;
foreach ($dataProvider->getModels() as $one) {
    $total_today_summa = $total_today_summa + $one->pay_summa;
}
?>

<div class="credit-plan-index">

    <div class="row">
        <div class="col-sm-4">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <?php if (Yii::$app->user->identity->role == 0): ?>
            <div class="col-sm-8">
                <table class="table table-bordered">
                    <tr>
                        <th class="table-dark"><?php echo date('j.m.Y', time()); ?></th>
                        <th class="table-active"><?php echo Yii::$app->params['today_index_summa'][Yii::$app->language]; ?></th>
                        <td><?php echo Yii::$app->formatter->asDecimal($total_today_summa, 0); ?> </td>
                        <th class="table-success"><?php echo Yii::$app->params['today_index_payed'][Yii::$app->language]; ?></th>
                        <td><?php echo Yii::$app->formatter->asDecimal($today_payments, 0); ?> </td>
                        <th class="table-warning"><?php echo Yii::$app->params['todat_index_unpayed'][Yii::$app->language]; ?></th>
                        <td><?php echo Yii::$app->formatter->asDecimal($total_today_summa - $today_payments, 0); ?> </td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'attribute' => 'credit_id',
            'value' => function ($data) {
                return Html::a($data->credit_id . ' от ' . Yii::$app->formatter->asDate($data->credit->doc_date_start, "php:d.m.Y"),['credit/view','id'=>$data->credit_id],['class'=>'btn btn-primary btn-sm']);
            },
            'format'=>'raw',
        ],
        [
            'attribute' => 'company_id',
            'value' => function ($data) {
                return $data->company->name;
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name'),
        ],
        [
            'attribute' => 'client_id',
            'value' => function ($data) {
                if(isset($data->client->id)){
                    return $data->client->fullname;
                } else {
                    return 'НЕТ КЛИЕНТА';
                }

            }
        ],
        [
            'attribute' => 'client_phone',
            'header' => Yii::$app->params['labels_phone'][Yii::$app->language],
            'value' => function ($data) {
                if(isset($data->client->id)){
                    return $data->client->phone;
                } else {
                    return 'НЕТ КЛИЕНТА';
                }
            },
        ],
        [
            'attribute' => 'created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->created, "php:d.m.Y");
            },
        ],
        [
            'attribute' => 'pay_summa',
            'value' => function ($data) {
                return Yii::$app->formatter->asDecimal($data->pay_summa, 0);
            }
        ],
        [
            'attribute' => 'pay_status',
            'value' => function ($data) {
                return Yii::$app->params['pay_status'][Yii::$app->language][$data->pay_status];
            },
            'filter' => Yii::$app->params['pay_status'][Yii::$app->language],
        ],
        //'summa_real',
        //'summa_bonus',
        //'is_sent_sms',
        //'yurist_goday',
    ];
    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => ['filename' => $this->title . '-' . date('d.m.Y')],
        ],
        'filename' => $this->title . '-' . date('d.m.Y')
    ]);

    // You can choose to render your own GridView separately
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm', 'style' => 'font-size:0.9em;text-align:center;'],
        'rowOptions' => function ($data) {
            return ['class' => Yii::$app->params['pay_status_class'][$data->pay_status]];
        },
        'columns' => $gridColumns
    ]);
    ?>


</div>
