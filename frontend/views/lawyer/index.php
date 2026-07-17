<?php

use yii\bootstrap4\Modal;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['labels_lawyer_views'][$lang][$status_id];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
/*Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'title' => 'Изменить время',
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => false, 'keyboard' => true]
]);
$content = $this->render('modal_form', [
    'item' => $dataProvider->getModels()
]);
echo "<div id='modalContent'><div style='text-align:center'>" . $content . "</div></div>";
Modal::end();
*/ ?>

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
                    <th class="table-active">Количество:</th>
                    <td><?= Yii::$app->formatter->asDecimal($totalCount, 0) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <form action="<?= \yii\helpers\Url::to(['index', 'id' => $status_id]) ?>" method="get">
        <input type="text" name="id" value="<?=$status_id?>" hidden="hidden">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="begin_date"><?= Yii::$app->params['labels_doc_date_start'][$lang] ?></label>
                    <input type="date" class="form-control" id="begin_date" name="Search[begin_date]">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="end_date"><?= Yii::$app->params['labels_doc_date_end'][$lang] ?></label>
                    <input type="date" class="form-control" id="end_date" name="Search[end_date]">
                </div>
            </div>
            <div class="col mt-4">
                <a href="<?= \yii\helpers\Url::to(['index', 'id' => $status_id]) ?>" class="btn btn-primary w-100">
                    <?= Yii::$app->params['labels_reset_button'][$lang] ?>
                </a>
            </div>
            <div class="col mt-4">
                <button type="submit"
                        class="btn btn-success w-100"><?= Yii::$app->params['header_search_button'][$lang] ?></button>
            </div>
        </div>
    </form>
    <?php
    $gridColumns = [
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
            'attribute' => 'credit_type_id',
            'value' => function ($data) {
                return $data->credit->creditType->name;
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\CreditType::find()->where(['!=', 'id', 7])->all(), 'id', 'name')
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
            'attribute' => 'client_phone',
            'header' => Yii::$app->params['labels_phone'][Yii::$app->language],
            'value' => function ($data) {
                return $data->client->phone;
            },
        ],
        [
            'header' => Yii::$app->params['payment_date'][$lang],
            'attribute' => 'created',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->created, "php:d.m.Y");
            },
        ],
        [
            'attribute' => 'pay_summa',
            'value' => function ($data) {
                return Yii::$app->formatter->asDecimal($data->pay_summa, 0);
            },
            'footer' => \common\models\Payments::getTotalCount($dataProvider->models, 'pay_summa'),
        ],
        [
            'header' => 'Сумма оплаты',
            'value' => function ($data) {
                $sum = (new Query)->select(['credit_plan_id', 'SUM(amount) as amount'])
                    ->from('payments')
                    ->where(['credit_plan_id' => $data->id])
                    ->one();
                return Yii::$app->formatter->asDecimal($sum['amount'], 0);
            },
            'format' => 'raw',
        ],
        [
            'header' => 'Остаток',
            'format' => 'raw',
            'value' => function ($data) {
                $result = \common\models\CreditPlan::getTotalPayment($data->pay_summa, $data->id);
                return Yii::$app->formatter->asDecimal($result, 0);
            },
            'contentOptions' => ['style' => 'width:100px']
        ],
        [
            'attribute' => 'yurist_goday',
            'value' => function ($data) {
                return date('d.m.Y', $data->yurist_goday);
            },
            'contentOptions' => ['class' => 'table-warning'],
            'format' => 'raw',
        ],
        [
            'attribute' => 'content',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->content;
            }
        ],
        //'summa_real',
        //'summa_bonus',
        [
            'header' => 'Смс',
            'value' => function ($data) {
                if ($data->is_sent_sms == 0) {
                    return '<i class="fa fa-check btn btn-success btn-sm" aria-hidden="true"></i>';
                } else {
                    return '<i class="fa fa-times btn btn-danger btn-sm" aria-hidden="true"></i>';
                }
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'pay_status',
            'value' => function ($data) {
                return Yii::$app->params['pay_status'][Yii::$app->language][$data->pay_status];
            },
            'contentOptions' => ['style' => 'width: 80px']
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{all}',
            'buttons' => [
                'all' => function ($url, $model, $key) use ($lang) {
                    $menuItems = [
                        /*['label' => Yii::$app->params['labels_lawyer_change'][$lang][5], 'url' => ['/credit-plan/status', 'id' => $model->id, 'status' => 5], 'linkOptions' => [
                            'data-confirm' => 'Подтвердите действие!!!',
                            'style' => 'color:blue;'
                        ]],
                        ['label' => Yii::$app->params['labels_lawyer_change'][$lang][7], 'url' => ['/credit-plan/status', 'id' => $model->id, 'status' => 7], 'linkOptions' => [
                            'data-confirm' => 'Подтвердите действие!!!',
                            'style' => 'color:green;'
                        ]],*/
                        ['label' => 'Примечание', 'linkOptions' => [
                            'data' => [
                                'target' => '#editColumn' . $model->id,
                                'toggle' => 'modal'
                            ]
                        ], 'url' => '#'],
                        ['label' => 'Предупредительное письмо', 'url' => ['/credit-invoice/warning', 'id' => $model->credit_id, 'plan_id' => $model->id]],

                    ];
                    /*if (Yii::$app->user->identity->role === 0) {
                        $menuItems[] = ['label' => 'Изменить дату юриста', 'url' => '#', 'linkOptions' => [
                            'data' => [
                                'target' => '#modal',
                                'toggle' => 'modal'
                            ],
                            'class' => 'showModalButton'
                        ]];
                    }*/
                    for ($i = 3; $i < 7; $i++) {
                        if ($model->pay_status === $i or Yii::$app->user->identity->role === 0) {
                            $menuItems[] = ['label' => Yii::$app->params['labels_lawyer_change'][$lang][$i + 1], 'url' => ['/credit-plan/status', 'id' => $model->id, 'status' => $i + 1], 'linkOptions' => [
                                'data-confirm' => 'Подтвердите действие!!!',
                                'style' => 'color:blue;'
                            ]];
                        }
                    }
                    if ($model->is_sent_sms == 0):
                        $menuItems[] = ['label' => 'Отключить СМС', 'url' => ['/credit-plan/status', 'id' => $model->id, 'status' => 1]];
                    endif;
                    if ($model->is_sent_sms == 1):
                        $menuItems[] = ['label' => 'Включить СМС', 'url' => ['/credit-plan/status', 'id' => $model->id, 'status' => 0]];
                    endif;


                    return \yii\bootstrap4\ButtonDropdown::widget([
                        'encodeLabel' => false, // if you're going to use html on the button label
                        'label' => 'Действие',
                        'dropdown' => [
                            'encodeLabels' => false, // if you're going to use html on the items' labels
                            'items' => $menuItems,
                            'options' => [
                                'class' => 'dropdown-menu-right', // right dropdown
                            ],
                        ],
                        'options' => [
                            'class' => 'btn-default',   // btn-success, btn-info, et cetera
                        ],
                        'split' => true,    // if you want a split button
                    ]);
                },
                'format' => 'raw',
            ],
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

    // You can choose to render your own GridView separately
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-sm text-center', 'style' => 'font-size:0.9em;text-align:center;'],
        'showFooter' => true,
        'columns' => $gridColumns
    ]);
    ?>
</div>

<?php
foreach ($dataProvider->getModels() as $item):
    $contents = \common\models\CreditPlanContents::find()->where(['credit_plan_id' => $item->id])->orderBy(['id' => 3])->all();
    ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal<?= $item->client->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel"> <?= Yii::$app->params['detail_information'][$lang] ?></h5>
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
    <div class="modal fade" id="editColumn<?= $item->id ?>" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Изменить примечание</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $form = \yii\widgets\ActiveForm::begin(['action' => Url::to(['/credit-plan/update', 'id' => $item->id])]); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'content')->textInput(['value' => $item->content]) ?>
                </div>
                <div class="modal-footer">
                    <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-success btn-block']) ?>
                </div>
                <?php \yii\widgets\ActiveForm::end(); ?>

                <hr>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Дата создания</th>
                        <th>Примечание</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    foreach ($contents as $value): ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= date('d.m.Y', $value->created) ?></td>
                            <td><?= $value->content ?></td>
                            <td>
                                <?php if (Yii::$app->user->identity->role === 0): ?>
                                    <a href="<?= Url::to(['delete-content', 'content_id' => $value->id]) ?>"
                                       class="btn btn-sm btn-danger">Удалить</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>

