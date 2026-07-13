<?php

use common\models\ClientCards;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
$lang = Yii::$app->language;
$this->title = $model->fullname;
$params = Yii::$app->params;
if ($model->client_type == 0):
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
else:
    $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['guar']];
endif;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-view">
    <div class="row">
        <div class="col-md-4">
            <h1><?= Html::encode($title . ': ' . $this->title) ?></h1>
        </div>
        <div class="col-md-2">
            <?php
            if (Yii::$app->user->identity->role == 0): ?>
                <?php if ($model->is_blacklist = 0): ?>
                    <a href="<?= Url::to(['to-blacklist', 'id' => $model->id]) ?>" class="btn btn-outline-danger"
                       data-method="post" data-confirm="Подтвердите действие!!!">
                        <?= $params['labels_to_blacklist'][$lang] ?>
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['from-blacklist', 'id' => $model->id]) ?>" class="btn btn-danger"
                       data-method="post" data-confirm="Подтвердите действие!!!">
                        <?= $params['labels_to_blacklist'][$lang] ?>
                    </a>
                <?php endif; endif; ?>

        </div>
        <div class="col-md-2">
            <?php if ($model->is_blacklist == 0): ?>
                <?php if (strlen($model->passport_pinfl) == 14): ?>
                    <a href="<?= \yii\helpers\Url::to(['/credit/create', 'id' => $model->id]) ?>"
                       class="btn btn-success btn-block"><?= $params['make_credit'][$lang] ?></a>
                <?php else: ?>
                    <button class="btn btn-warning btn-block"> <?= $params['fill_pinfl'][$lang] ?></button>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-warning btn-block"><?= $params['labels_in_blacklist'][$lang] ?></button>
            <?php endif; ?>
        </div>
        <div class="col-md-2 text-center">
            <p>
                <button class="<?= $params['client_credit_score_class'][$model->credit_score] ?>">Кредитный
                    счёт: <?= $params['client_credit_score'][$lang][$model->credit_score] ?> <i
                            class="fa fa-star-half-o" aria-hidden="true"></i></button>
            </p>
        </div>
        <?php if (Yii::$app->user->identity->role == 0): ?>
        <div class="col-md-2 text-right">
            <p>
                <?= Html::a($params['update'][$lang], ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </p>
        </div>
        <?php endif;?>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <nav class="mb-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true"><?= $params['information'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile"
                            aria-selected="false"><?= $params['labels_extra_phone'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-img-tab" data-toggle="tab" data-target="#nav-img"
                            type="button" role="tab" aria-controls="nav-img"
                            aria-selected="false"><?= $params['labels_passports'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-extra_img-tab" data-toggle="tab" data-target="#nav-extra_img"
                            type="button" role="tab" aria-controls="nav-extra_img"
                            aria-selected="false"><?= $params['labels_extra_files'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                            type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false"><?= $params['labels_payments'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-cards-tab" data-toggle="tab" data-target="#nav-current-photos"
                            type="button" role="tab" aria-controls="nav-cards"
                            aria-selected="false"><?= $params['labels_current_photo'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-cards-tab" data-toggle="tab" data-target="#nav-cards"
                            type="button" role="tab" aria-controls="nav-cards"
                            aria-selected="false"><?= $params['labels_cards'][$lang] ?>
                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'id',
                            'fullname',
                            [
                                'attribute' => 'created',
                                'value' => function ($data) {
                                    return date('d.m.Y H:i:s', $data->created);
                                }
                            ],
                            [
                                'attribute' => 'client_type',
                                'value' => function ($data) {
                                    return Yii::$app->params['client_type'][Yii::$app->language][$data->client_type];
                                }
                            ],
                            'phone',
                            [
                                'attribute' => 'birthday',
                                'value' => function ($data) {
                                    return Yii::$app->formatter->asDate($data->birthday, "php:d.m.Y");
                                }
                            ],
                            'passport_numb',
                            'passport_pinfl',
                            'passport_whose',
                            [
                                'attribute' => 'passport_enddate',
                                'value' => function ($data) {
                                    return $data->passport_enddate;
                                }
                            ],
                            'address',
                            'image',
                        ],
                    ]) ?>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <?= $this->render('_form_phone', ['model' => $client_phone]) ?>
                    <table class="table table-sm table-bordered text-center">
                        <thead>
                        <tr>
                            <th><?= $params['labels_phone'][$lang] ?></th>
                            <th><?= $params['labels_content'][$lang] ?></th>
                            <th><?= $params['labels_created'][$lang] ?></th>
                            <th>
                                <?= Yii::$app->params['send_warning_sms'][$lang] ?>
                            </th>
                            <?php if(Yii::$app->user->identity->role === 0):?>
                            <th></th>
                            <?php endif;?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($view_phone as $item): ?>
                            <tr>
                                <td><?= $item->numb ?></td>
                                <td><?= $item->content ?></td>
                                <td><?= date('d.m.Y H:i:s', $item->created) ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#warningSmsModal-<?=$item->id?>">
                                        <i class="fa fa-comments"></i>
                                    </button>
                                </td>
                                <?php if(Yii::$app->user->identity->role === 0):?>
                                <td>
                                    <a href="<?= \yii\helpers\Url::to(['/client/phone-delete', 'item' => $item->id, 'view' => $model->id]) ?>"
                                       class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i></a>
                                </td>
                                <?php endif;?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php foreach ($view_phone as $item): ?>
                        <div class="modal fade" id="warningSmsModal-<?=$item->id?>" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel"><?= Yii::$app->params['sign_doc_number'][$lang] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="<?= Url::to(['send-warning', 'phone_id' => $item->id]) ?>"
                                          method="post">
                                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <?= Html::dropDownList('credit_id', '', \yii\helpers\ArrayHelper::map(\common\models\Credit::find()->where(['client_id' => $item->client_id])->asArray()->all(), 'id', 'id'), ['class' => 'form-control', 'prompt' => 'Выберите договор','required'=>true]) ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal"><?= Yii::$app->params['close'][$lang] ?></button>
                                            <button type="submit"
                                                    class="btn btn-primary"><?= Yii::$app->params['send_sms'][$lang] ?></button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="tab-pane fade" id="nav-img" role="tabpanel" aria-labelledby="nav-img-tab">
                    <div class="row">
                        <div class="col-md-12" style="height: 600px;">
                            <embed src="<?= Yii::$app->request->baseUrl . "/uploads/client_documents/$model->image" ?>"
                                   style="width: 100%; height: 100%;" alt="">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-extra_img" role="tabpanel" aria-labelledby="nav-extra_img-tab">
                    <div class="row">
                        <div class="col-md-12" style="height: 600px;">
                            <?= $this->render('_form_extra_docs', ['model' => $extra_files, 'user_id' => $model->id]) ?>
                            <table class="table table-sm table-bordered">
                                <thead class="table-active">
                                <th><?= $params['labels_created'][$lang] ?></th>
                                <th><?= $params['labels_file'][$lang] ?></th>
                                <th></th>
                                <th></th>
                                </thead>
                                <tbody>
                                <?php foreach ($view_files as $item): ?>
                                    <tr>
                                        <td><?= date('d.m.Y', $item->created) ?></td>
                                        <td><?= $item->image ?></td>
                                        <td style="width: 8%;" class="text-center">
                                            <a href="<?= Yii::$app->request->baseUrl . '/uploads/client_documents/' . $item->image ?>"
                                               class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td style="width: 8%;" class="text-center">
                                            <?php if (Yii::$app->user->identity->role === 0): ?>
                                                <a href="<?= \yii\helpers\Url::to(['/client/file-delete', 'id' => $item->id]) ?>"
                                                   class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                     aria-labelledby="nav-contact-tab">
                    <table class="table table-bordered table-sm text-center">
                        <thead>
                        <tr class="table-primary">
                            <th><?= $params['labels_credit_id'][$lang] ?></th>
                            <th><?= $params['payment_date'][$lang] ?></th>
                            <th><?= $params['labels_pay_type'][$lang] ?></th>
                            <th><?= $params['amount'][$lang] ?></th>
                            <th><?= $params['labels_content'][$lang] ?></th>
                            <th><?= $params['labels_user'][$lang] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $summa = 0;
                        foreach ($payment as $item): ?>
                            <tr>
                                <td><?= $item->credit_id ?></td>
                                <td><?= date('d.m.Y', $item->created) ?></td>
                                <td><?= $params['method'][$lang][$item->method_id] ?></td>
                                <td><?= Yii::$app->formatter->asDecimal($item->amount, 0) ?></td>
                                <td><?php echo $item->content ?></td>
                                <td><?= $item->user->username ?></td>
                            </tr>
                            <?php
                            $summa = $summa + $item->amount;
                        endforeach; ?>
                        <tr class="table-primary">
                            <td></td>
                            <td></td>
                            <td>Итого:</td>
                            <td><?= Yii::$app->formatter->asDecimal($summa, 0) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-cards" role="tabpanel"
                     aria-labelledby="nav-cards-tab">
                    <h2><?= $params['labels_cards'][$lang] ?></h2>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#cardsCollapse" aria-expanded="false"
                                    aria-controls="collapseExample">
                                Добавить новую карту
                            </button>
                        </div>
                        <div class="col-md-7 mt-2">
                            <div class="collapse" id="cardsCollapse">
                                <div class="card card-body">
                                    <?= $this->render('_form_cards', ['model' => new ClientCards(), 'client_id' => $model->id]) ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped text-center">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Название карты</th>
                                    <th>Номер карты</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;
                                foreach ($cards as $item): ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $item->card_name ?></td>
                                        <td><?= substr_replace($item->card_number, str_repeat("X", 8), 4, 8); ?></td>
                                        <td>
                                            <?php if($item->algenix_card_id):?>
                                            <button type="button" class="btn btn-info btn-sm connectCreditBtn" data-url="<?=Url::to(['connect-card','id'=>$item->id])?>"><?=$params['connect_credits'][$lang]?></button>
                                            <?php else:?>
                                            <button class="btn btn-warning btn-sm " type="button">Карта тастикланмаган</button>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                    <?php $i++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-current-photos" role="tabpanel"
                     aria-labelledby="nav-cards-tab">
                    <h2><?= $params['labels_current_photo'][$lang] ?></h2>
                    <table class="table table-sm table-bordered">
                        <thead class="table-active">
                        <tr>
                            <th><?= $params['labels_created'][$lang] ?></th>
                            <th><?= $params['labels_credit_id'][$lang] ?></th>
                            <th><?= $params['labels_file'][$lang] ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($current_photos as $item): ?>
                            <tr>
                                <td><?= date('d.m.Y', $item->created) ?></td>
                                <td><?= $item->credit_id ?></td>
                                <td><?= $item->image ?></td>
                                <td style="width: 8%;" class="text-center">
                                    <a href="<?= Yii::$app->request->baseUrl . '/uploads/client_current_photos/' . $item->image ?>"
                                       class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td style="width: 8%;" class="text-center">
                                    <?php if (Yii::$app->user->identity->role === 0): ?>
                                        <a href="<?= \yii\helpers\Url::to(['/client/file-delete', 'id' => $item->id]) ?>"
                                           class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php if (isset($credits)):
                    foreach ($credits as $item):?>
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-pills card-header-pills justify-content-between">
                                        <li class="nav-item align-self-center">
                                            <h6> <?= $params['labels_credit_id'][$lang] . $item->id ?>
                                                от <?= Yii::$app->formatter->asDate($item->doc_date_start, "php:d.m.Y") . '( ' . $item->doc_date_end . ' / ' . $item->pay_day . ' )' ?></h6>
                                        </li>
                                        <li class="nav-item align-self-center">
                                            <span class="badge <?= $params['credit_status_class'][$item->credit_status] ?>"><?= $params['credit_status'][$lang][$item->credit_status] ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-0 m-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm text-center"
                                                   style="font-size:0.9em;">
                                                <tr>
                                                    <th class="table-info"><?= $params['labels_doc_date_start'][$lang] ?>
                                                        :
                                                    </th>
                                                    <td> <?= Yii::$app->formatter->asDate($item->doc_date_start, "php:d.m.Y") ?></td>
                                                    <th class="table-active"><?= $params['labels_guarantor'][$lang] ?>
                                                        :
                                                    </th>
                                                    <td>
                                                        <?php if ($item->guarantor_id != null): ?>
                                                            <?= $item->guarantor->fullname ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <th class="table-active"><?= $params['labels_company'][$lang] ?>
                                                        :
                                                    </th>
                                                    <td> <?= $item->company->company_title ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-active"
                                                        colspan=""><?= $params['labels_percent'][$lang] ?>:
                                                    </th>
                                                    <td><?= $item->percent ?> %</td>
                                                    <th class="table-active"><?= $params['labels_user'][$lang] ?>
                                                        :
                                                    </th>
                                                    <td colspan="3"> <?= $item->user->username ?></td>
                                                </tr>

                                            </table>
                                        </div>
                                        <div class="col-md-9">
                                            <?php
                                            $payed = $item->getInfo($item->id);
                                            $ostatok = $item->doc_total_price - $item->prepaid_summa - $payed;
                                            ?>
                                            <table class="table table-sm text-center">
                                                <tr>
                                                    <th><?= $params['credit_amount'][$lang] ?> </th>
                                                    <th class="table-info"><?= $params['credit_amount_real'][$lang] ?></th>
                                                    <th class="table-success"><?= $params['payed_by_credit'][$lang] ?> </th>
                                                    <th class="table-primary"><?= $params['unpayed_by_credit'][$lang] ?> </th>
                                                </tr>
                                                <tr>
                                                    <td><?= Yii::$app->formatter->asDecimal($item->doc_total_price, 0) ?> </td>
                                                    <td> <?= Yii::$app->formatter->asDecimal(($item->doc_total_price - $item->prepaid_summa), 0) ?> </td>
                                                    <td><?php echo yii::$app->formatter->asDecimal($payed); ?>  </td>
                                                    <td><?php echo yii::$app->formatter->asDecimal($ostatok); ?>  </td>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="col-md-3">
                                            <a class="btn btn-primary btn-sm w-100"
                                               href="<?= \yii\helpers\Url::to(['credit/view', 'id' => $item->id]) ?>"><?= $params['redirect'][$lang] ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                endif;
                ?>
            </div>

        </div>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'link-card-modal',
    'title' => 'Привязать карту',
    'size' => Modal::SIZE_LARGE,
    'options' => ['tabindex' => false],
]);
echo '<div class="modal-body p-0"></div>';
Modal::end(); ?>
<script>
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.connectCreditBtn');
        if (!link) return;

        e.preventDefault();
        const url = link.dataset.url;
        const modalEl = document.getElementById('link-card-modal');
        const modalBody = modalEl.querySelector('.modal-body');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        modalBody.innerHTML = '<div class="p-4 text-center">Загрузка…</div>';
        modal.show();

        fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(r => r.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(() => {
                modalBody.innerHTML = '<div class="p-4 text-danger">Ошибка загрузки</div>';
            });
    });
</script>