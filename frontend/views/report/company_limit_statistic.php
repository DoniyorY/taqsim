<?php

use common\models\CompanyPlanLimit;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $contractCompanies array */
/* @var $paymentCompanies array */
/* @var $month string */

$this->title = 'Статистика лимитов компаний';
$this->params['breadcrumbs'][] = $this->title;
$formatter = Yii::$app->formatter;
?>

<style>
    .company-limit-statistic {
        display: flex;
        flex-wrap: wrap;
        gap: 22px 48px;
        margin-top: 20px;
    }
    .company-limit-card {
        width: 47%;
        min-width: 620px;
    }
    .company-limit-table {
        width: 100%;
        border-collapse: collapse;
        color: #000;
        font-weight: 700;
        font-size: 18px;
    }
    .company-limit-table th,
    .company-limit-table td {
        border: 3px solid #000;
        padding: 2px 4px;
    }
    .company-limit-table .header-row th {
        background: #92d050;
        font-size: 20px;
    }
    .company-limit-table .header-row .company-name {
        text-align: left;
        width: 42%;
    }
    .company-limit-table .header-row .limit-cell {
        text-align: right;
        width: 30%;
    }
    .company-limit-table .header-row .percent-cell {
        background: #ff0000;
        text-align: right;
        width: 14%;
    }
    .company-limit-table .subheader-row th,
    .company-limit-table .total-row td {
        background: #c6e0b4;
    }
    .company-limit-table .name-cell {
        width: 42%;
    }
    .company-limit-table .sum-cell,
    .company-limit-table .monthly-cell {
        text-align: right;
        width: 28%;
    }
    .company-limit-table .row-percent-cell {
        color: #ff0000;
        text-align: right;
        width: 10%;
    }
    .company-limit-table .total-label {
        text-align: right;
    }
    @media (max-width: 1300px) {
        .company-limit-card {
            width: 100%;
            min-width: 0;
        }
    }
</style>

<?php
$renderCompanyTables = function ($companies) use ($formatter) {
    foreach ($companies as $company): ?>
        <div class="company-limit-card">
            <table class="company-limit-table">
                <tr class="header-row">
                    <th class="company-name"><?= Html::encode($company['company_name']) ?></th>
                    <th class="limit-cell" colspan="2"><?= $formatter->asDecimal($company['limit'], 0) ?></th>
                    <th class="percent-cell"><?= $company['percent'] === null ? '#DIV/0!' : $formatter->asDecimal($company['percent'], 1) . '%' ?></th>
                </tr>
                <tr class="subheader-row">
                    <th>MAGAZIN NOMI</th>
                    <th>SOVDASI</th>
                    <th>%</th>
                    <th>Oylik</th>
                </tr>
                <?php foreach ($company['rows'] as $row): ?>
                    <tr>
                        <td class="name-cell"><?= Html::encode($row['credit_type_name']) ?></td>
                        <td class="sum-cell"><?= $formatter->asDecimal($row['summa'], 0) ?></td>
                        <td class="row-percent-cell"><?= $formatter->asDecimal($row['salary_percent'], 1) ?>%</td>
                        <td class="monthly-cell"><?= $formatter->asDecimal($row['salary'], 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td class="total-label">JAMI :</td>
                    <td class="sum-cell"><?= $formatter->asDecimal($company['total'], 0) ?></td>
                    <td></td>
                    <td class="monthly-cell"><?= $formatter->asDecimal($company['salary_total'], 0) ?></td>
                </tr>
            </table>
        </div>
    <?php endforeach;
};
?>

<div class="report-company-limit-statistic">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['company-limit-statistic'], 'get', ['class' => 'form-inline', 'style' => 'margin-bottom: 15px;']) ?>
        <div class="form-group">
            <?= Html::label('Месяц лимита', 'company-limit-month', ['class' => 'control-label', 'style' => 'margin-right: 10px;']) ?>
            <?= Html::input('month', 'month', $month, ['id' => 'company-limit-month', 'class' => 'form-control']) ?>
        </div>
        <?= Html::submitButton('Фильтр', ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>
        <?= Html::a('Сбросить', ['company-limit-statistic'], ['class' => 'btn btn-default', 'style' => 'margin-left: 5px;']) ?>
    <?= Html::endForm() ?>

    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
            <a href="#contract-limit-statistic" role="tab" data-toggle="tab">
                <?= Html::encode(CompanyPlanLimit::typeLabels()[CompanyPlanLimit::TYPE_CONTRACTS]) ?>
            </a>
        </li>
        <li>
            <a href="#payment-limit-statistic" role="tab" data-toggle="tab">
                <?= Html::encode(CompanyPlanLimit::typeLabels()[CompanyPlanLimit::TYPE_PAYMENTS]) ?>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="contract-limit-statistic">
            <div class="company-limit-statistic">
                <?php $renderCompanyTables($contractCompanies); ?>
            </div>
        </div>
        <div class="tab-pane" id="payment-limit-statistic">
            <div class="company-limit-statistic">
                <?php $renderCompanyTables($paymentCompanies); ?>
            </div>
        </div>
    </div>
</div>
