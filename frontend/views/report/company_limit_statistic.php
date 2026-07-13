<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $companies array */

$this->title = 'Статистика лимитов компаний';
$this->params['breadcrumbs'][] = $this->title;
$formatter = Yii::$app->formatter;
?>

<style>
    .company-limit-statistic {
        display: flex;
        flex-wrap: wrap;
        gap: 22px 48px;
    }
    .company-limit-card {
        width: 47%;
        min-width: 520px;
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
        border: 2px solid #000;
        padding: 2px 4px;
    }
    .company-limit-table .header-row th {
        background: #92d050;
        font-size: 20px;
        text-align: left;
    }
    .company-limit-table .subheader-row th,
    .company-limit-table .total-row td {
        background: #c6e0b4;
    }
    .company-limit-table .name-cell {
        width: 46%;
    }
    .company-limit-table .limit-cell,
    .company-limit-table .sum-cell {
        text-align: right;
        width: 20%;
    }
    .company-limit-table .row-percent-cell {
        color: #ff0000;
        text-align: right;
        width: 12%;
    }
    .company-limit-table .total-label {
        text-align: right;
    }
    @media (max-width: 1200px) {
        .company-limit-card {
            width: 100%;
            min-width: 0;
        }
    }
</style>

<div class="report-company-limit-statistic">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="company-limit-statistic">
        <?php foreach ($companies as $company): ?>
            <?php
            $credit = $company['credit'];
            $payment = $company['payment'];
            $total = ($credit['summa'] ?? 0) + ($payment['summa'] ?? 0);
            ?>
            <div class="company-limit-card">
                <table class="company-limit-table">
                    <tr class="header-row">
                        <th colspan="4"><?= Html::encode($company['company_name']) ?></th>
                    </tr>
                    <tr class="subheader-row">
                        <th>MAGAZIN NOMI</th>
                        <th>LIMIT</th>
                        <th>SOVDASI</th>
                        <th>%</th>
                    </tr>
                    <tr>
                        <td class="name-cell">План оформленных договоров</td>
                        <td class="limit-cell"><?= $formatter->asDecimal($credit['limit'] ?? 0, 0) ?></td>
                        <td class="sum-cell"><?= $formatter->asDecimal($credit['summa'] ?? 0, 0) ?></td>
                        <td class="row-percent-cell"><?= $credit && $credit['percent'] !== null ? $formatter->asDecimal($credit['percent'], 1) . '%' : '#DIV/0!' ?></td>
                    </tr>
                    <tr>
                        <td class="name-cell">План по сбору денег с договоров</td>
                        <td class="limit-cell"><?= $formatter->asDecimal($payment['limit'] ?? 0, 0) ?></td>
                        <td class="sum-cell"><?= $formatter->asDecimal($payment['summa'] ?? 0, 0) ?></td>
                        <td class="row-percent-cell"><?= $payment && $payment['percent'] !== null ? $formatter->asDecimal($payment['percent'], 1) . '%' : '#DIV/0!' ?></td>
                    </tr>
                    <tr class="total-row">
                        <td class="total-label">JAMI :</td>
                        <td class="limit-cell"><?= $formatter->asDecimal(($credit['limit'] ?? 0) + ($payment['limit'] ?? 0), 0) ?></td>
                        <td class="sum-cell"><?= $formatter->asDecimal($total, 0) ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>
