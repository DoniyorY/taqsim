<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$date = Yii::$app->formatter->asDate($model->credit->doc_date_start, "php:d.m.Y");
$this->title = Yii::$app->params['sign_doc_number_view'][Yii::$app->language] . $model->credit_id . ' от ' . $model->credit->doc_date_start;
/**
 * @var $model common\models\CreditSign
 */
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
echo Yii::$app->view->render('index')
?>

<h4 class="text-left"><?= Html::encode('Информация о договоре') ?></h4>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-sm text-center" style="font-size: 11px;">
            <tr>
                <th class="table-info">
                    <i class="fa fa-address-card-o"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_client'][$lang] ?>
                </th>
                <td>
                    <?= $model->credit->client->fullname ?>
                </td>

                <th class="table-info">
                    <i class="fa fa-address-card-o"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_guarantor'][$lang] ?>
                </th>
                <td>
                    <?php if (!is_null($model->credit->guarantor_id)) {
                        echo $model->credit->guarantor->fullname;
                    } ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-file-text-o"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_credit_id'][$lang] ?>
                </th>
                <td>

                    <?php $value = str_replace(',','.',$model->credit->pay_day)?>
                    <?= $model->credit_id . ' от ' . $model->credit->doc_date_start . ' / ' . Yii::$app->formatter->asDate($value, "php:d") . ' день месяца' ?>

                </td>
                <th class="table-info">
                    <i class="fa fa-calendar"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_month_count'][$lang] ?>
                </th>
                <td>
                    <?= $model->credit->month_count ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <?= Yii::$app->params['labels_total_price'][$lang] ?>
                </th>
                <td>
                    <?= Yii::$app->formatter->asDecimal($model->credit->doc_total_price, 0) ?>
                </td>
                <th class="table-info">
                    <?= Yii::$app->params['labels_prepaid_summa'][$lang] ?>
                </th>
                <td>
                    <?= Yii::$app->formatter->asDecimal($model->credit->prepaid_summa, 0) ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-map-marker"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_company'][$lang] ?>
                </th>
                <td>
                    <?= $model->credit->company->company_title ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-user-circle-o"
                       aria-hidden="true"></i> <?= Yii::$app->params['labels_user'][$lang] ?>
                </th>
                <td>
                    <?= $model->credit->user->username ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <h4><?= Yii::$app->params['labels_sign'][$lang] ?>:</h4>
        <hr>
        <div class="row">
            <div class="col-md-6 col-6">
                <h4><?= Yii::$app->params['labels_sign_photo'][$lang] ?></h4>
                <?php if (!$photos):?>
                <?php $form = ActiveForm::begin(['action' => Url::to(['add-photo']), 'method' => 'post']); ?>
                <?php
                echo Html::activeHiddenInput($photo, 'client_id', ['value' => $model->credit->client_id]);
                echo Html::activeHiddenInput($photo, 'credit_id', ['value' => $model->credit_id])
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($photo, 'imageFile')->fileInput() ?>
                    </div>
                    <div class="col-md-6 text-end">
                        <?= Html::submitButton(Yii::$app->params['labels_save'][$lang], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end();  else:?>
                    <div class="card" style="width: 18rem;">
                        <img src="<?= "$base/uploads/client_current_photos/$photos->image" ?>" class="card-img-top" alt="..."
                             style="object-fit: cover;">
                        <div class="card-body p-2">
                            <?php if ($model->credit->credit_status < 2): ?>
                                <a href="<?= Url::to(['delete-photo','photo_id'=>$photos->id]) ?>" data-confirm="Подтвердите действие!!!"
                                   class="btn btn-danger w-100"><?= Yii::$app->params['labels_sign_delete_photo'][$lang] ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($photos): ?>
                <div class="col-md-6 col-6">
                    <?php if (empty($model->client_sign)): ?>
                        <a data-toggle="modal" href="#ClientSign" onclick="openCreateClientModal('creditor')"
                           class="btn btn-success btn-block"><?= Yii::$app->params['client_sign'][$lang] ?></a>
                    <?php else: ?>
                        <h5><?= Yii::$app->params['client_sign'][$lang] ?></h5>
                        <div style="border: gray 1px solid">
                            <img src="<?= $model->client_sign ?>"
                                 alt="<?= Yii::$app->params['client_sign'][$lang] ?>"
                                 style="width: 100%;">
                        </div>
                        <?php if ($model->credit->credit_status < 2): ?>
                            <a data-toggle="modal" href="#ClientSign" onclick="openCreateClientModal('creditor')"
                               class="btn btn-success btn-block mt-3"><?= Yii::$app->params['change_client_sign'][$lang] ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="col-md-6 col-6">
                <?php if (isset($model->credit->guarantor_id)): ?>
                    <?php if (empty($model->guarantor_sign)): ?>
                        <a data-toggle="modal" href="#ClientSign" onclick="openCreateClientModal('guarantor')"
                           class="btn btn-success btn-block"><?= Yii::$app->params['guarantor_sign'][$lang] ?></a>
                    <?php else: ?>
                        <h5><?= Yii::$app->params['guarantor_sign'][$lang] ?></h5>
                        <div style="border: gray 1px solid">
                            <img src="<?= $model->guarantor_sign ?>"
                                 alt="<?= Yii::$app->params['guarantor_sign'][$lang] ?>"
                                 style="width: 100%;">
                        </div>
                        <?php if ($model->credit->credit_status < 2): ?>
                            <a data-toggle="modal" href="#ClientSign"
                               onclick="openCreateClientModal('guarantor')"
                               class="btn btn-success btn-block mt-3"><?= Yii::$app->params['change_guarantor_sign'][$lang] ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!--!Sign Modal-->
<div class="modal fade" id="ClientSign"
     role="dialog" aria-labelledby="ClientSignFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="ClientSignFormModalLabel"><?= Yii::$app->params['labels_client_singing'][$lang] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= \yii\helpers\Url::to(['/sign/sign', 'id' => $model->id]) ?>">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                           value="<?= Yii::$app->request->csrfToken; ?>"/>
                    <!-- Content -->
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h3><span id="client_create_form_type"></span></h3>
                                <p><?= Yii::$app->params['labels_submit_sign'][$lang] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="sig-canvas" width="450" height="270" style="margin-left: -10px;">
                                    Get a better browser, bro.
                                </canvas>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea hidden id="sig-dataUrl" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <img id="sig-image" src="" alt="Your signature will go here!"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center row">
                        <div class="col-12">
                            <button type="button" class="btn btn-info btn-block mb-2"
                                    id="sig-submitBtn"><?= Yii::$app->params['submit_sign_button'][$lang] ?>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-secondary btn-block"
                                    id="sig-clearBtn"><?= Yii::$app->params['labels_reset_button'][$lang] ?>
                            </button>
                        </div>
                        <div class="col-8">
                            <button type="submit"
                                    class="btn btn-success btn-block"><?= Yii::$app->params['send_sign_button'][$lang] ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>