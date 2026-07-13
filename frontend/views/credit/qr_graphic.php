<?php
$lang = Yii::$app->language;
$this->title = 'Шартнома № ' . $model->id . ' - ' . $model->doc_date_start
?>

<div class="row">
    <div class="col-md-12">
        <center>
            <img src="<?=Yii::$app->request->baseUrl . '/uploads/logo.png'?>" style="width:150px;" alt="">
        </center>
    </div>
</div>

<hr/>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-sm text-center" style="font-size: 14px;">
            <tr>
                <th class="table-info">
                    <i class="fa fa-address-card-o" aria-hidden="true"></i> <?=Yii::$app->params['labels_client'][$lang]?>:
                </th>
                <td>
                    <?= $model->client->fullname ?>
                </td>

                <th class="table-info">
                    <i class="fa fa-address-card-o" aria-hidden="true"></i> <?=Yii::$app->params['labels_guarantor'][$lang]?>:
                </th>
                <td>
                    <?php if (!is_null($model->guarantor_id)){
                        echo $model->guarantor->fullname;
                    } ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i> <?=Yii::$app->params['labels_credit_id'][$lang]?>:
                </th>
                <td>
                    <?= $model->id . ' /  ' . $model->doc_date_start . ' / ' . Yii::$app->formatter->asDate($model->pay_day, "php:d") . ' ойнинг куни' ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-calendar" aria-hidden="true"></i> <?=Yii::$app->params['labels_month_count'][$lang]?>:
                </th>
                <td>
                    <?= $model->month_count ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-usd" aria-hidden="true"></i> <?=Yii::$app->params['labels_total_price'][$lang]?>
                </th>
                <td>
                    <?= Yii::$app->formatter->asDecimal($model->doc_total_price, 0);  ?>
                </td>
                <th class="table-info">
                    <?=Yii::$app->params['labels_prepaid_summa'][$lang]?>:
                </th>
                <td>
                    <?= Yii::$app->formatter->asDecimal($model->prepaid_summa, 0) ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-map-marker" aria-hidden="true"></i> <?=Yii::$app->params['labels_company'][$lang]?>:
                </th>
                <td>
                    <?= $model->company->company_title ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> <?=Yii::$app->params['labels_user'][$lang]?>:
                </th>
                <td>
                    <?= $model->user->username ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <table class="table table-sm table-bordered text-center">
            <thead>
            <tr class="table-primary">
                <th><?= Yii::$app->params['month'][$lang] ?></th>
                <th><?= Yii::$app->params['payment_date'][$lang] ?></th>
                <th><?= Yii::$app->params['amount'][$lang] ?></th>
                <th class="table-success"><?= Yii::$app->params['credit_payed_amount'][$lang] ?></th>
                <th class="table-danger"><?= Yii::$app->params['credit_dept_month'][$lang] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $payed_summa = 0;
            $unpayed_summa = 0;
            $ostatok = $model->doc_total_price;
            foreach ($credit_plans as $i => $plan):?>
                <tr>
                    <td><?= $i + 1 ?> <?= Yii::$app->params['month'][$lang] ?></td>
                    <td><?= Yii::$app->formatter->asDate($plan->created, "php:d.m.Y") ?></td>
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
                        <?php
                        $unpayed_summa = $unpayed_summa+ $plan->pay_summa - $total_month;
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr class="bg-dark text-white">
                <th colspan="3"><?=Yii::$app->params['total'][$lang]?>:</th>
                <th><?= Yii::$app->formatter->asDecimal($payed_summa, 0) ?></th>
                <th><?= Yii::$app->formatter->asDecimal($unpayed_summa, 0) ?></th>
            </tr>
            </tfoot>
        </table>
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

