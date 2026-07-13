<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['labels_lawyer_views'][$lang][7];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-lg',
    'closeButton' => [
        'id'=>'close-button',
        'class'=>'close',
        'data-dismiss' =>'modal',
    ],
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => false, 'keyboard' => true]
]);
echo "<div id='modalContent'><div style='text-align:center'>"
    . Html::img('@web/img/radio.gif')
    . "</div></div>";
Modal::end();
?>

<div class="credit-plan-index">

    <div class="row">
        <div class="col-sm-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-4">
            <table class="table table-bordered">
                <tr>
                    <th class="table-active"><?php echo Yii::$app->params['total_late_summa'][Yii::$app->language]; ?> </th>
                    <td><?php echo yii::$app->formatter->asDecimal($total, 0) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-2"></div>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($data) {
            return ['table-sm text-center'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'format' => 'raw',
                'attribute' => 'credit_id',
                'value' => function ($data) {
                    return Html::a($data->credit_id . ' от ' . Yii::$app->formatter->asDate($data->credit->doc_date_start, "php:d.m.Y"),
                        ['/credit/view', 'id' => $data->credit_id],
                        ['class' => 'btn btn-primary btn-sm', 'target' => '_blank']);
                },

            ],

            [
                'attribute' => 'company_id',
                'value' => function ($data) {
                    return $data->company->name;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name'),
            ],
            [
                'header' => Yii::$app->params['labels_user'][Yii::$app->language],
                'value' => function ($data) {
                    return $data->credit->user->username;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username'),
            ],

            [
                'format' => 'raw',
                'attribute' => 'client_id',
                'value' => function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal' . $data->client->id . '">
                            ' . $data->client->fullname . '
</button>';
                }
            ],
            [
                'header' => Yii::$app->params['labels_phone'][Yii::$app->language],
                'value' => function ($data) {
                    return $data->client->phone;
                },
            ],
            [
                'header'=> Yii::$app->params['payment_date'][$lang],
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
                'attribute' => 'yurist_goday',
                'value' => function ($data) {
                    return date('d.m.Y', $data->yurist_goday);
                },
                'contentOptions' => ['class' => 'table-warning'],
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => function ($data) {
                    return '<a class="btn btn-warning btn-sm" href="' . Url::to(['/credit-invoice/warning', 'id' => $data->credit_id, 'plan_id' => $data->id]) . '">Предупредительное письмо</a>';
                }
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->content . ' ' . Html::button('<i class="fa fa-pencil">', ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => "#editColumn$data->id", 'type' => 'button']);
                }
            ],
            //'summa_real',
            //'summa_bonus',
            [
                'header' => 'Смс',
                'value' => function ($data) {
                    if ($data->is_sent_sms == 0) {
                        return 'Да';
                    } else {
                        return 'Нет';
                    }
                }
            ],
            [

                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->is_sent_sms == 0) {
                        return Html::a('откл смс', ['/credit-plan/status', 'id' => $data->id, 'status' => 1,], ['class' => 'btn-sm btn btn-success btn-block']);
                    } else {
                        return Html::a('вкл смс', ['/credit-plan/status', 'id' => $data->id, 'status' => 0,], ['class' => 'btn-sm btn btn-danger btn-block']);
                    }

                }
            ],

        ],
    ]); ?>
</div>

<?php
foreach ($dataProvider->getModels() as $item):
    ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal<?= $item->client->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> <?= Yii::$app->params['detail_information'][$lang] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm text-left">
                        <tr>
                            <th style="width: 200px;">
                                <?= Yii::$app->params['labels_fullname'][$lang] ?>
                            </th>
                            <td>
                                <?= $item->client->fullname ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?= Yii::$app->params['labels_address'][$lang] ?>
                            </th>
                            <td>
                                <?= $item->client->address ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?= Yii::$app->params['labels_passport'][$lang] ?>
                            </th>
                            <td>
                                <?= $item->client->passport_numb ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?= Yii::$app->params['labels_extra_phone'][$lang] ?>
                            </th>
                            <td>
                                <?php

                                foreach ($extra_phone as $phone) {
                                    if ($phone->client_id == $item->client_id) {
                                        echo $phone->numb . ' (' . $phone->content . '); <br>';
                                    }
                                } ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit content column -->
    <div class="modal fade" id="editColumn<?=$item->id?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Изменить примечание</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $form = \yii\widgets\ActiveForm::begin(['action' => Url::to(['/credit-plan/update', 'id' => $item->id])]); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'content')->textInput(['value' => $item->content])?>
                </div>
                <div class="modal-footer">
                    <?=Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-success btn-block'])?>
                </div>
                <?php \yii\widgets\ActiveForm::end();?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

