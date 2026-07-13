<?php

use yii\helpers\Html;
use common\models\CreditItem;
use yii\helpers\Url;
use frontend\components\ProgressWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Credit
 * @var $payment common\models\Payments
 */
$lang = Yii::$app->language;
$this->title = 'Кредит № ' . $model->id . ' от ' . $model->doc_date_start;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['credits'][$lang], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="credit-view">
    <div class="container-fluid">
        <?= ProgressWidget::widget(['status' => $model->credit_status]) ?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <h3 style="font-weight: 400;"><?= Html::encode($this->title) ?></h3>
            <p><strong> <?= Yii::$app->params['labels_content'][$lang] ?>:</strong> <i><?= $model->content ?></i></p>
        </div>

        <div class="col-md-2">
            <?php if (Yii::$app->user->identity->role === 0): ?>
                <?php if ($model->credit_status != -2): ?>
                    <a href="<?= Url::to(['/credit/to-basket', 'id' => $model->id]) ?>"
                       data-method="post" class="btn btn-danger btn-block">
                        <i class="fa fa-trash"></i> Отправить в корзину</a>
                <?php endif; ?>
                <?php if ($model->credit_status == -2): ?>
                    <a href="<?= Url::to(['/credit/reset-basket', 'id' => $model->id]) ?>"
                       data-method="post" class="btn btn-warning btn-block">
                        <i class="fa fa-trash"></i> Вернуть с корзины</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if (Yii::$app->user->identity->role == 0):?>
            <?php if ($model->credit_status == 2): ?>
                <div class="col-md-2">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rejectModal">
                        <?= Yii::$app->params['credit_reject'][$lang] ?>
                    </button>
                </div>
            <?php elseif ($model->credit_status == -1): ?>
                <div class="col-md-2">
                    <a href="<?= Url::to(['revorke', 'id' => $model->id]) ?>" data-confirm="Подтвердите действие!!!"
                       class="btn btn-success">
                        <?= Yii::$app->params['revorke'][$lang] ?>
                    </a>
                </div>
            <?php endif; ?>
            <!-- Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="rejectModalLabel"><?= Yii::$app->params['credit_reject'][$lang] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?= Url::to(['credit/reject', 'id' => $model->id]) ?>" method="post">
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="reject_text"><?= Yii::$app->params['reject_reason'][$lang] ?></label>
                                    <textarea class="form-control" name="Reject[reject_reason]"
                                              id="reject_text"> </textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal"><?= Yii::$app->params['close'][$lang] ?></button>
                                <button type="submit"
                                        class="btn btn-success"><?= Yii::$app->params['labels_save'][$lang] ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif;?>


        <div class="col text-end text-right">
            <ul class="nav justify-content-end">
                <?php if ($model->credit_status === 0 or Yii::$app->user->identity->role === 0): ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link btn btn-primary"
                           href="<?= Url::to(['/credit/update', 'id' => $model->id]) ?>"><i class="fa fa-undo"
                                                                                            aria-hidden="true"></i>
                            <?= Yii::$app->params['credit_update'][$lang] ?></a>
                    </li>
                <?php endif; ?>
                <li class="nav-item mr-1">
                    <a class="btn nav-link btn-info" data-toggle="modal" data-target="#exampleModal">
                        <?= Yii::$app->params['more'][$lang] ?>
                    </a>
                </li>
                <?php $has = \common\models\CreditInvoice::findOne(['credit_id' => $model->id]);
                if (is_null($has) && $model->credit_status === 1):?>
                    <li class="nav-item mr-1">
                        <a class="nav-link btn btn-primary"
                           data-method="post"
                           href="<?= Url::to(['/credit/status', 'id' => $model->id, 'status' => 0]) ?>"><?= Yii::$app->params['credit_settings_update'][$lang] ?></a>
                    </li>
                    <li class="nav-item mr-1">
                        <a id="make-plan-btn" class="nav-link btn btn-success"
                           href="<?= Url::to(['/credit/make-plan', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_make_plan'][$lang] ?></a>
                    </li>
                <?php elseif (!is_null($has) && $model->credit_status === 1): ?>

                    <?php if (isset($signs)): ?>
                        <?php if (!empty($signs->client_sign) && is_null($model->guarantor_id)): ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link btn btn-success"
                                   data-method="post"
                                   href="<?= Url::to(['/credit/status', 'id' => $model->id, 'status' => 2]) ?>"
                                   data-confirm="Подтвердите действие"><?= Yii::$app->params['credit_close'][$lang] ?></a>
                            </li>
                        <?php elseif (!empty($signs->client_sign) && isset($model->guarantor_id) && !empty($signs->guarantor_sign)): ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link btn btn-success"
                                   data-method="post"
                                   href="<?= Url::to(['/credit/status', 'id' => $model->id, 'status' => 2]) ?>"
                                   data-confirm="Подтвердите действие"><?= Yii::$app->params['credit_close'][$lang] ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link btn btn-danger"
                           data-method="post" href="<?= Url::to(['/credit/delete-plan', 'credit' => $model->id]) ?>"
                           data-confirm="Подтвердите действие"><?= Yii::$app->params['credit_delete_plan'][$lang] ?></a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle btn btn-secondary" data-toggle="dropdown" href="#" role="button"
                       aria-expanded="false"><?= Yii::$app->params['credit_contacts'][$lang] ?></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item"
                           href="<?= Url::to(['/credit/sign', 'credit_id' => $model->id]) ?>"><?= Yii::$app->params['credit_signs'][$lang] ?></a>
                        <?php if ($credit_plans): ?>
                            <a class="dropdown-item"
                               href="<?= Url::to(['/credit-invoice/view1', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_invoice'][$lang] ?></a>
                        <?php endif; ?>
                        <a class="dropdown-item"
                           href="<?= Url::to(['/credit-invoice/contract', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_client_contract'][$lang] ?></a>
                        <?php if (isset($model->guarantor_id)): ?>
                            <a class="dropdown-item"
                               href="<?= Url::to(['/credit-invoice/guarantor', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_guarantor_contract'][$lang] ?></a>
                        <?php endif; ?>
                        <a class="dropdown-item"
                           href="<?= Url::to(['/credit-invoice/payment-plan', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_plan_invoice'][$lang] ?></a>
                        <?php if (isset($model->guarantor_id)): ?>
                            <a class="dropdown-item"
                               href="<?= Url::to(['/credit-invoice/letter', 'id' => $model->id]) ?>"><?= Yii::$app->params['credit_letter'][$lang] ?></a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <?php

            use Da\QrCode\QrCode;
            use yii\widgets\ActiveForm;

            $url_base = 'https://taqsimsavdo.uz';
            $url_action = '/credit/graphic';
            $url = '?token=' . $model->token;
            $qrCode = (new QrCode($url_base . $url_action . $url))
                //->setLogo(__DIR__ . 'http://localhost:8888/clinics_multi/frontend/web/logo/1630226301.png')
                //->setEncoding('UTF-8')
                //->setLogoWidth(60)
                ->setSize(100)
                ->setMargin(1)
                ->useForegroundColor(0, 0, 0);

            // now we can display the qrcode in many ways
            // saving the result to a file:

            $qrCode->writeFile(__DIR__ . '/code.png'); // writer defaults to PNG when none is specified

            ?>
            <?php
            // or even as data:uri url
            echo '<img style="width:100%;" src="' . $qrCode->writeDataUri() . '">';
            ?>
            <!-- right -->
        </div>
        <div class="col-md-11">
            <table class="table table-sm table-bordered text-left">
                <tr>
                    <th class="table-active"><i class="fa fa-address-card-o"
                                                aria-hidden="true"></i> <?= Yii::$app->params['labels_client'][$lang] ?>
                        :
                    </th>
                    <td>
                        <span data-container="body" data-toggle="popover" data-placement="top"
                              data-content="<?= $model->client->phone ?>">
                            <?= $model->client->fullname ?>
                        </span>
                        <a href="<?= Url::to(['/client/view', 'id' => $model->client->id]) ?>"
                           class="btn btn-sm btn-primary ml-2" target="_blank">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                    <th class="table-active"><i class="fa fa-address-card-o" aria-hidden="true"></i>
                        <?= Yii::$app->params['labels_guarantor'][$lang] ?>:
                    </th>
                    <td>
                        <?php if ($model->guarantor_id != null): ?>
                            <span><?= $model->guarantor->fullname ?></span>
                            <a href="<?= Url::to(['/client/view', 'id' => $model->guarantor_id]) ?>" target="_blank"
                               class="btn btn-sm btn-primary ml-2">
                                <i class="fa fa-eye"></i>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <th class="table-active"><i class="fa fa-user-circle-o"
                                                aria-hidden="true"></i> <?= Yii::$app->params['labels_user'][$lang] ?>:
                    </th>
                    <td><?= (isset($model->user->username)) ? $model->user->username : '-'; ?></td>
                    <th class="table-active"><i class="fa fa-map-marker" aria-hidden="true"
                                                style="margin-right: 2px;"></i> <?= Yii::$app->params['labels_region'][$lang] ?>
                        :
                    </th>
                    <td><?= $model->region->name ?></td>
                    <td class="table-active"><?= Yii::$app->params['labels_status'][$lang] ?>:
                    </td>
                    <td><span class="badge <?= Yii::$app->params['credit_status_class'][$model->credit_status] ?>"
                              style="font-size: 14px;"><?= Yii::$app->params['credit_status'][$lang][$model->credit_status] ?></span>
                    </td>
                </tr>
                <tr>
                    <th class="table-active"><i class="fa fa-calendar" aria-hidden="true"></i>
                        <?= Yii::$app->params['labels_doc_date_start'][$lang] ?>:
                    </th>
                    <td>
                        <?= Yii::$app->formatter->asDate($model->doc_date_start, "php:d.m.Y") ?>
                    </td>
                    <th class="table-active"><i class="fa fa-calendar"
                                                aria-hidden="true"></i> <?= Yii::$app->params['labels_doc_date_end'][$lang] ?>
                        :
                    </th>
                    <td><?= Yii::$app->formatter->asDate($model->doc_date_end, "php:d.m.Y") ?></td>
                    <th class="table-active"><i class="fa fa-calendar-minus-o"
                                                aria-hidden="true"></i> <?= Yii::$app->params['labels_pay_day'][$lang] ?>
                        :
                    </th>
                    <td><?= date('d', strtotime($model->pay_day)) ?></td>
                    <th class="table-active"><i class="fa fa-building-o"
                                                aria-hidden="true"></i> <?= Yii::$app->params['labels_company'][$lang] ?>
                        :
                    </th>
                    <td><?= $model->company->name ?></td>
                    <th class="table-active">
                        <?= Yii::$app->params['labels_credit_type'][$lang] ?>
                    </th>
                    <td>
                        <?= $model->creditType->name; ?>
                    </td>
                </tr>
                <?php if ($model->credit_status != 0): ?>
                    <tr>
                        <th class="table-info">
                            <?= Yii::$app->params['labels_self_price'][$lang] ?>:
                        </th>
                        <td>
                            <?= Yii::$app->formatter->asDecimal($model->self_price, 0) ?>
                        </td>
                        <th class="table-info">
                            <?= Yii::$app->params['labels_percent'][$lang] ?>:
                        </th>
                        <td>
                            <?= $model->percent . ' %' ?>
                        </td>
                        <th class="table-info">
                            <?= Yii::$app->params['labels_month_count'][$lang] ?>:
                        </th>
                        <td>
                            <?= $model->month_count ?>
                        </td>
                        <th class="table-info">
                            <?= Yii::$app->params['labels_prepaid_summa'][$lang] ?>:
                        </th>
                        <td>
                            <?= Yii::$app->formatter->asDecimal($model->prepaid_summa, 0) ?>
                        </td>
                        <th class="table-info">
                            <?= Yii::$app->params['labels_method'][$lang] ?>:
                        </th>
                        <td>
                            <?php if ($model->method_id) {
                                echo Yii::$app->params['method'][$lang][$model->method_id];
                            } ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>

            <div class="row">
                <div class="col-md-4">
                    <?php if ($model->credit_status != 0): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong> <?= Yii::$app->params['labels_doc_total_price'][$lang] ?>:</strong><br/>
                            <?= Yii::$app->formatter->asDecimal($model->doc_total_price) ?>
                            <small> (<?= $model->doc_total_text ?> )</small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-2" style="display: none">
                    <?php if ($model->credit_status != 0): ?>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong> <?= Yii::$app->params['labels_self_price'][$lang] ?>:</strong><br/>
                            <?= Yii::$app->formatter->asDecimal($model->doc_total_price - $model->prepaid_summa) ?>
                        </div>


                    <?php endif; ?>
                </div>
                <?php if (isset($signs)): ?>
                    <?php if (empty($signs->client_sign)): ?>
                        <div class="col-md-3">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-ban"
                                   aria-hidden="true"></i> <?= Yii::$app->params['client_no_sign'][$lang] ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-3">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle-o"
                                   aria-hidden="true"></i> <?= Yii::$app->params['client_yes_sign'][$lang] ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($signs->guarantor_sign)): ?>
                        <div class="col-md-3">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-ban"
                                   aria-hidden="true"></i> <?= Yii::$app->params['garant_no_sign'][$lang] ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-3">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle-o"
                                   aria-hidden="true"></i> <?= Yii::$app->params['garant_yes_sign'][$lang] ?>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="col-md-6">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-ban" aria-hidden="true"></i>
                                <?= Yii::$app->params['error_no_signs'][$lang] ?>
                            </strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <nav class="mb-3">
                <div class="nav nav-pills" id="nav-tab" role="tablist">
                    <button class="nav-link <?php if ($model->credit_status == 2): echo 'active'; endif; ?>"
                            id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="true">
                        <?= Yii::$app->params['credit_payment_plan'][$lang] ?>
                    </button>
                    <button class="nav-link <?php if ($model->credit_status <= 1): echo 'active'; endif; ?>"
                            id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                            type="button"
                            role="tab" aria-controls="nav-home"
                            aria-selected="false"><?= Yii::$app->params['credit_items'][$lang] ?>
                    </button>
                    <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                            type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false"><?= Yii::$app->params['credit_payment_history'][$lang] ?>
                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade <?php if ($model->credit_status == 2): echo 'show active'; endif; ?>"
                     id="nav-profile" role="tabpanel"
                     aria-labelledby="nav-profile-tab">
                    <table class="table table-sm table-bordered text-center table-hover">
                        <thead>
                        <tr class="table-primary">
                            <th><?= Yii::$app->params['month'][$lang] ?></th>
                            <th><?= Yii::$app->params['payment_date'][$lang] ?></th>
                            <th><?= Yii::$app->params['amount'][$lang] ?></th>
                            <th class="table-success"><?= Yii::$app->params['credit_payed_amount'][$lang] ?></th>
                            <th class="table-danger"><?= Yii::$app->params['credit_dept_month'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_status'][$lang] ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $payed_summa = 0;
                        $unpayed_summa = 0;
                        $total_summa = 0;
                        $ostatok = $model->doc_total_price;
                        foreach ($credit_plans as $i => $plan):?>
                            <tr>
                                <td><?= $i + 1 ?> <?= Yii::$app->params['month'][$lang] ?> <?=(Yii::$app->user->id === 10)?' | '. $plan->id:''?></td>
                                <td><?= Yii::$app->formatter->asDate($plan->created, "php:d.m.Y H:i") ?></td>
                                <td><?= Yii::$app->formatter->asDecimal($plan->pay_summa, 0) ?></td>
                                <td class="table-success">

                                    <?php $total_month = 0;
                                    foreach ($payment_history as $one): ?>
                                        <?php if ($one->credit_plan_id == $plan->id): ?>
                                            <?php $total_month = $total_month + $one->amount; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php echo yii::$app->formatter->asDecimal($total_month, 0); ?>
                                    <?php $payed_summa = $payed_summa + $total_month; ?>
                                    <?php $payed_plan = $plan->pay_summa - $total_month; ?>
                                </td>
                                <td class="table-danger">
                                    <?= Yii::$app->formatter->asDecimal($plan->pay_summa - $total_month, 0) ?>
                                </td>
                                <td><?= Yii::$app->params['pay_status'][$lang][$plan->pay_status] ?></td>

                                <td>
                                    <?php if ($model->credit_status != -2): ?>
                                        <?php if ($payed_plan != 0): ?>
                                            <button data-toggle="modal" data-target="#exampleModal<?= $i ?>"
                                                    class="btn btn-success btn-sm" title="Оплачено">
                                                <?= Yii::$app->params['labels_to_pay'][$lang] ?>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($payed_plan == 0 && $plan->pay_status == 0): ?>
                                            <a href="<?= Url::to(['/credit/plan-status', 'id' => $plan->id, 'status' => 1]) ?>"
                                               data-method="post" class="btn btn-danger btn-sm"
                                               title="<?= Yii::$app->params['labels_close_month'][$lang] ?>">
                                                <?= Yii::$app->params['labels_close_month'][$lang] ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php $ostatok = $plan->pay_summa - $total_month; ?>
                                        <?php $unpayed_summa = $unpayed_summa + $ostatok; ?>
                                    <?php endif; ?>
                                </td>


                                <td>
                                    <?php if ($model->credit_status != -2): ?>
                                        <?php if (Yii::$app->user->identity->role == 0): ?>
                                            <?php if ($plan->pay_status != 1 && $plan->pay_status != 2): ?>
                                                <a href="<?= Url::to(['/credit/plan-status', 'id' => $plan->id, 'status' => 2]) ?>"
                                                   data-method="post" class="btn btn-danger btn-sm"
                                                   title="<?= Yii::$app->params['labels_close_month'][$lang] ?>">
                                                    <i class="fa fa-times"
                                                       aria-hidden="true"></i> <?= Yii::$app->params['labels_close_month'][$lang] ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (Yii::$app->user->identity->role == 0): ?>
                                            <?php if ($plan->pay_status == 1 || $plan->pay_status == 2): ?>
                                                <a href="<?= Url::to(['/credit/plan-status', 'id' => $plan->id, 'status' => 0]) ?>"
                                                   data-method="post" class="btn btn-info text-white btn-sm"
                                                   title="<?= Yii::$app->params['labels_canceled_month'][$lang] ?>">
                                                    <i class="fa fa-times"
                                                       aria-hidden="true"></i> <?= Yii::$app->params['labels_canceled_month'][$lang] ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php if (Yii::$app->user->id === 10):?>
                            <td>
                                <a href="<?= Url::to(['delete-plan', 'id' => $plan->id]) ?>" class="btn btn-warning btn-sm" data-confirm="Really?" data-method="post">
                                    Delete
                                </a>
                            </td>
                            <?php endif;?>
                            </tr>
                            <?php $total_summa = $total_summa + $plan->pay_summa; endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr class="bg-dark text-white">
                            <th></th>
                            <th></th>
                            <th><?= Yii::$app->formatter->asDecimal($total_summa, 0) ?></th>
                            <th><?= Yii::$app->formatter->asDecimal($payed_summa, 0) ?></th>
                            <th><?= Yii::$app->formatter->asDecimal($unpayed_summa, 0) ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
                <div class="tab-pane fade <?php if ($model->credit_status <= 1): echo 'show active'; endif; ?>"
                     id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="<?php if ($model->credit_status === 0): echo 'col-md-8'; elseif ($model->credit_status >= 1): echo 'col-md-12'; endif; ?>">

                            <table class="table table-bordered text-center table-sm">
                                <thead>
                                <tr class="table-active">
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        <?= Yii::$app->params['labels_item_title'][$lang] ?>
                                    </th>
                                    <th>
                                        <?= Yii::$app->params['labels_item_count'][$lang] ?>
                                    </th>
                                    <th>
                                        <?= Yii::$app->params['labels_item_amount'][$lang] ?>
                                    </th>
                                    <th>
                                        <?= Yii::$app->params['total'][$lang] ?>
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total_items_summa = 0;
                                foreach ($credit_item as $i => $item): ?>

                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $item->title ?></td>
                                        <td><?= $item->count ?></td>
                                        <td><?= Yii::$app->formatter->asDecimal($item->summa, 0) ?></td>
                                        <td><?= Yii::$app->formatter->asDecimal($item->summa * $item->count, 0) ?></td>
                                        <td>
                                            <?php if ($model->credit_status == 0): ?>
                                                <a href="<?= Url::to(['/credit/item-delete', 'id' => $item->id, 'credit' => $model->id]) ?>"
                                                   class="btn btn-success btn-sm btn-danger btn-sm">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $total_items_summa = $total_items_summa + ($item->summa * $item->count); ?>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <th></th>
                                    <th><?= Yii::$app->params['total_amount'][$lang] ?></th>
                                    <th></th>
                                    <th></th>
                                    <th><?= Yii::$app->formatter->asDecimal($total_items_summa, 0) ?></th>
                                    <input onchange="" type="hidden" value="<?php echo $total_items_summa; ?>"
                                           id="total_item_summa"/>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                            <?php
                            if ($model->credit_status == 0) {
                                echo $this->render('_form_items', [
                                    'model' => new CreditItem(),
                                    'credit' => $model,
                                ]);
                            } ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            if ($model->credit_status == 0) {
                                echo $this->render('_form_detail', [
                                    'model' => $model,
                                ]);
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <table class="table table-sm table-bordered text-center ">
                        <thead>
                        <tr class="table-success">
                            <th><?= Yii::$app->params['payment_date'][$lang] ?></th>
                            <th><?= Yii::$app->params['amount'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_method'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_pay_type'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_company'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_user'][$lang] ?></th>
                            <th><?= Yii::$app->params['labels_content'][$lang] ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        foreach ($payment_history as $item):?>
                            <tr>
                                <td><?= Yii::$app->formatter->asDate($item->created, "php:d.m.Y") ?></td>
                                <td><?= Yii::$app->formatter->asDecimal($item->amount, 0) ?></td>
                                <td><?= Yii::$app->params['method'][$lang][$item->method_id] ?></td>
                                <td><?= Yii::$app->params['pay_type'][$lang][$item->pay_type] ?></td>
                                <td><?= $item->company->name ?></td>
                                <td><?= $item->user->username ?></td>
                                <td><?= $item->content ?></td>
                                <td><a href="<?= \yii\helpers\Url::to(['/credit-invoice/cheque', 'id' => $item->id]) ?>"
                                       class="btn btn-primary btn-sm">Чек</a></td>
                            </tr>
                            <?php $total = $total + $item->amount;
                        endforeach; ?>
                        <tr class="table-active">
                            <td><?= Yii::$app->params['total'][$lang] ?>:</td>
                            <td><?= Yii::$app->formatter->asDecimal($total, 0) ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Detail -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel"><?= Yii::$app->params['detail_information'][$lang] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm text-center">
                            <tr>
                                <th class="table-active"><?= Yii::$app->params['labels_guar_name'][$lang] ?>:</th>
                                <th class="table-active"><?= Yii::$app->params['labels_guar_type'][$lang] ?>:</th>
                                <th class="table-active"><?= Yii::$app->params['labels_guar_count'][$lang] ?>:</th>
                                <th class="table-active"><?= Yii::$app->params['labels_guar_summa'][$lang] ?>:</th>
                            </tr>
                            <tr>
                                <td><?= $model->guar_name ?></td>
                                <td><?= $model->guar_type ?></td>
                                <td><?= $model->guar_count ?></td>
                                <td><?= Yii::$app->formatter->asDecimal($model->guar_summa, 0) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><?= Yii::$app->params['witness_seller'][$lang] ?></h6>
                        <table class="table table-bordered table-sm">
                            <tr class="table-active">
                                <th><?= Yii::$app->params['labels_fullname'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_seller_fullname)) {
                                        echo $model->witness_seller_fullname;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::$app->params['labels_phone'][$lang] ?></th>
                                <td><?php if (!is_null($model->witness_seller_phone)) {
                                        echo $model->witness_seller_phone;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr class="table-active">
                                <th><?= Yii::$app->params['labels_address'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_seller_address)) {
                                        echo $model->witness_seller_address;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::$app->params['labels_passport'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_seller_passport)) {
                                        echo $model->witness_seller_passport;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><?= Yii::$app->params['witness_customer'][$lang] ?></h6>
                        <table class="table table-bordered table-sm">
                            <tr class="table-active">
                                <th><?= Yii::$app->params['labels_fullname'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_customer_fullname)) {
                                        echo $model->witness_customer_fullname;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::$app->params['labels_phone'][$lang] ?></th>
                                <td><?php if (!is_null($model->witness_customer_phone)) {
                                        echo $model->witness_customer_phone;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr class="table-active">
                                <th><?= Yii::$app->params['labels_address'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_customer_address)) {
                                        echo $model->witness_customer_address;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::$app->params['labels_passport'][$lang] ?></th>
                                <td><?php if (!empty($model->witness_customer_passport)) {
                                        echo $model->witness_customer_passport;
                                    } else {
                                        echo 'Не задано';
                                    } ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$ostatok = $model->doc_total_price;
foreach ($credit_plans as $i => $plan):
    ?>
    <?php $total_month = 0;
    foreach ($payment_history as $one): ?>
        <?php if ($one->credit_plan_id == $plan->id): ?>
            <?php $total_month = $total_month + $one->amount; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php $ostatok = $plan->pay_summa - $total_month; ?>

    <!-- Modal payment-->
    <div class="modal fade" id="exampleModal<?= $i ?>" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel"><?= Yii::$app->params['credit_modal_title'][$lang] ?></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-c']); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($payment, 'amount')
                                ->textInput(['value' => $ostatok, 'min' => 0]) ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            if ($lang == 'ru') {
                                $method = [0 => 'Наличные', 1 => 'Карта', 2 => 'Оплата Атмос', 3 => 'Оплата Algenix', 4 => 'Оплата MIB'];
                            } else {
                                $method = [0 => 'Накт', 1 => 'Карта', 2 => 'Атмосдан тулов', 3 => 'Algenix тулов', 4 => 'MIB тулов'];
                            }
                            echo $form->field($payment, 'method_id')->dropDownList($method, ['prompt' => 'Выберите метод', 'required' => 'required']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($payment, 'company_id')
                                ->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name'),
                                    ['value' => $model->company_id, 'readonly' => true, 'disabled'=>true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?php $i = $i + 1;
                            echo $form->field($payment, 'content')->textarea(['value' => "Оплата по графику договора № $model->id  -  $model->doc_date_start, по плану $i месяц | Дата плана: " . Yii::$app->formatter->asDate($plan->created, "php:d.m.Y")]) ?>
                        </div>
                    </div>
                    <?= $form->field($payment, 'credit_plan_id')->textInput(['value' => $plan->id, 'hidden' => true])->label(false) ?>
                    <?php
                    echo $form->field($payment, 'credit_id')->textInput(['hidden' => true, 'value' => $model->id])->label(false);
                    echo $form->field($payment, 'credit_type_id')->textInput(['hidden' => true, 'value' => $model->credit_type_id])->label(false);
                    ?>
                    <?= Html::submitButton(Yii::$app->params['to_pay'][$lang], ['class' => 'btn btn-block btn-success', 'id' => 'payment-submit-button']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        var button = document.getElementById('make-plan-btn');

        button.addEventListener('click', function (event) {
            // Установите кнопку в состояние disabled
            button.classList.add('disabled');
            button.style.pointerEvents = 'none'; // Отключает клики по кнопке

            // Если нужно, предотвратить переход по ссылке сразу
            event.preventDefault();

            // Создайте таймер, чтобы кнопка оставалась заблокированной до завершения перехода
            setTimeout(function () {
                window.location.href = button.href;
            }, 100); // Задержка в 100 миллисекунд, чтобы позволить стилистическим изменениям быть примененными
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('form-c');
        var submitButton = document.getElementById('payment-submit-button');

        submitButton.addEventListener('click', function (event) {
            // Деактивируем кнопку
            submitButton.classList.add('disabled');
            submitButton.style.pointerEvents = 'none'; // Блокируем клики
            submitButton.disabled = true; // Делаем кнопку неактивной

            // Убедимся, что форма отправляется
            form.submit();
        });
    });
</script>