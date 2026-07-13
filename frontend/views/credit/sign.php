
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered text-center" style="font-size: 14px;">
            <tr>
                <th class="table-info">
                    <i class="fa fa-address-card-o" aria-hidden="true"></i> Кредитор:
                </th>
                <td>
                    <?= $model->credit->client->fullname ?>
                </td>

                <th class="table-info">
                    <i class="fa fa-address-card-o" aria-hidden="true"></i> Гарант:
                </th>
                <td>
                    <?php if (!is_null($model->credit->guarantor_id)){
                        echo $model->credit->guarantor->fullname;
                    } ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Договор №:
                </th>
                <td>
                    <?= $model->credit_id . ' от ' . $model->credit->doc_date_start . ' / ' . Yii::$app->formatter->asDate($model->credit->pay_day, "php:d") . ' день месяца' ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-calendar" aria-hidden="true"></i> Кол-во месяцев:
                </th>
                <td>
                    <?= $model->credit->month_count ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-usd" aria-hidden="true"></i> Общая сумма / предоплата:
                </th>
                <td>
                    <?= Yii::$app->formatter->asDecimal($model->credit->self_price, 0) . ' / ' . Yii::$app->formatter->asDecimal($model->credit->prepaid_summa, 0) ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-percent" aria-hidden="true"></i> Процент:
                </th>
                <td>
                    <?= $model->credit->percent . '%' ?>
                </td>
            </tr>
            <tr>
                <th class="table-info">
                    <i class="fa fa-map-marker" aria-hidden="true"></i> Магазин:
                </th>
                <td>
                    <?= $model->credit->company->company_title ?>
                </td>
                <th class="table-info">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> Сотрудник:
                </th>
                <td>
                    <?= $model->credit->user->username ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <h4>Электронные подписи:</h4>
        <hr>
        <div class="row">
            <div class="col-md-6 col-6">
                <h5>Подпись кредитора</h5>
                <div>
                    <img src="<?= $model->client_sign ?>" alt="Подпись кредитора">
                </div>
            </div>
            <div class="col-md-6 col-6">
                <h5>Подпись Гаранта</h5>
                <div>
                    <img src="<?= $model->guarantor_sign ?>" alt="Подпись Поручителя">
                </div>
            </div>
        </div>

    </div>
</div>

