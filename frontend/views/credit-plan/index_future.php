<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['credits_plan_future'][$lang];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-7"></div>
        <?php if (Yii::$app->user->identity->role == 0): ?>
            <div class="col-4 text-end">
                <table class="table table-bordered">
                    <tr>
                        <th class="table-active"><?php echo Yii::$app->params['total_late_summa'][Yii::$app->language]; ?> </th>
                        <td><?php echo yii::$app->formatter->asDecimal($total, 0) ?></td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <form action="<?= \yii\helpers\Url::to(['credit-plan/future']) ?>">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="begin_date"><?=Yii::$app->params['labels_doc_date_start'][$lang]?></label>
                    <input type="date" class="form-control" id="begin_date" name="Search[begin_date]">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="end_date"><?=Yii::$app->params['labels_doc_date_end'][$lang]?></label>
                    <input type="date" class="form-control" id="end_date" name="Search[end_date]">
                </div>
            </div>
            <div class="col mt-4">
                <a href="<?= \yii\helpers\Url::to(['future']) ?>" class="btn btn-primary w-100">
                    <?=Yii::$app->params['labels_reset_button'][$lang]?>
                </a>
            </div>
            <div class="col mt-4">
                <button type="submit" class="btn btn-success w-100"><?=Yii::$app->params['header_search_button'][$lang]?></button>
            </div>
        </div>
    </form>
    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'attribute' => 'credit_id',
            'value' => function ($data) {

                return Html::a($data->credit_id . ' от ' . Yii::$app->formatter->asDate($data->credit->doc_date_start, "php:d.m.Y"), ['/credit/view', 'id' => $data->credit_id], ['target' => '_blank']);
            },
            'format' => 'raw'
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
                return $data->client->fullname;
            }
        ],
        [
            'attribute' => 'client_phone',
            'header' => Yii::$app->params['labels_phone'][Yii::$app->language],
            'value' => function ($data) {
                return $data->client->phone;
            },
        ],
        [
            'attribute' => 'created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->created, "php:d.m.Y H:i");
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
        'showFooter' => true,
        'rowOptions' => function ($data) {
            return ['class' => Yii::$app->params['pay_status_class'][$data->pay_status]];
        },
        'columns' => $gridColumns
    ]);
    ?>


</div>
