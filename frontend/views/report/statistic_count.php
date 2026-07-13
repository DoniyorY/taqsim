<?php
$lang = Yii::$app->language;
$this->title = 'Количество кредитов по месяцам: ' . Yii::$app->formatter->asDate($start, "php:d.m.Y") . ' - ' . Yii::$app->formatter->asDate($end, "php:d.m.Y");
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="statistic-count">
   <div class="row">
      <div class="col-md-6">
         <h1><?= Html::encode($this->title) ?></h1>
      </div>
   </div>

   <?php $form = ActiveForm::begin(['method' => 'get']) ?>
   <div class="row">
      <div class="col-md-5">
         <label for="date_begin">
            <?= Yii::$app->params['labels_doc_date_start'][$lang] ?>
         </label>
         <?= Html::input('date', 'date_begin', Yii::$app->formatter->asDate($start, "php:Y-m-d"), ['class' => 'form-control', 'id' => 'date_begin']) ?>
      </div>
      <div class="col-md-5">
         <label for="date_end">
            <?= Yii::$app->params['labels_doc_date_end'][$lang] ?>
         </label>
         <?= Html::input('date', 'date_end', Yii::$app->formatter->asDate($end, "php:Y-m-d"), ['class' => 'form-control', 'id' => 'date_end']) ?>
      </div>
      <div class="col-md-1">
         <?= Html::submitButton(Yii::$app->params['header_search_button'][$lang], ['class' => 'btn btn-primary btn-block mt-4']) ?>
      </div>
      <div class="col-md-1">
         <?= Html::a('Сбросить', ['/report/statistic-count'], ['class' => 'btn btn-warning btn-block mt-4 text-white']) ?>
      </div>
   </div>
   <?php ActiveForm::end(); ?>

   <ul class="nav nav-pills mt-3 mb-2" role="navigation" aria-label="Навигация по таблицам">
      <li class="nav-item">
         <a href="#credit-count-table" class="nav-link active">Количество кредитов</a>
      </li>
      <li class="nav-item">
         <a href="#contract-sum-table" class="nav-link">Сумма договоров</a>
      </li>
      <li class="nav-item">
         <a href="#payment-sum-table" class="nav-link">Сумма платежей</a>
      </li>
   </ul>

   <div class="mt-2" id="credit-count-table">
      <div class="d-flex justify-content-between align-items-center">
         <h3>Количество кредитов по количеству месяцев</h3>
         <button class="btn btn-primary btn-xsmall mb-2"
                 onclick="ExportToExcel('tbl_exporttable_to_xls', 'statistic-credit-count.xlsx')">
            <?= Yii::$app->params['export_to'][$lang] ?>
         </button>
      </div>
      <table class="table table-sm table-striped table-bordered text-center" border="1" id="tbl_exporttable_to_xls">
         <thead>
         <tr>
            <th style="border: 1px solid #000"></th>
            <th style="border: 1px solid #000" colspan="<?= count($monthCounts) + 1 ?>">Количество месяцев</th>
         </tr>
         <tr>
            <th style="border: 1px solid #000"><?= Yii::$app->params['labels_company'][$lang] ?></th>
            <?php foreach ($monthCounts as $monthCount): ?>
               <th style="border: 1px solid #000"><?= $monthCount ?></th>
            <?php endforeach; ?>
            <th style="border: 1px solid #000">Итого</th>
         </tr>
         </thead>
         <tbody>
         <?php foreach ($companies as $company): ?>
            <tr class="<?= $company['total'] <= 0 ? 'table-danger' : '' ?>">
               <td style="border: 1px solid #000; text-align: left"><?= Html::encode($company['name']) ?></td>
               <?php foreach ($monthCounts as $monthCount): ?>
                  <td style="border: 1px solid #000">
                     <?= Yii::$app->formatter->asDecimal($company['counts'][$monthCount] ?? 0, 0) ?>
                  </td>
               <?php endforeach; ?>
               <td style="border: 1px solid #000">
                  <?= Yii::$app->formatter->asDecimal($company['total'], 0) ?>
               </td>
            </tr>
         <?php endforeach; ?>
         </tbody>
         <tfoot class="table-dark">
         <tr>
            <td>Итого</td>
            <?php $grandTotal = 0; ?>
            <?php foreach ($monthCounts as $monthCount): ?>
               <?php
               $monthTotal = 0;
               foreach ($companies as $company) {
                   $monthTotal += $company['counts'][$monthCount] ?? 0;
               }
               $grandTotal += $monthTotal;
               ?>
               <td><?= Yii::$app->formatter->asDecimal($monthTotal, 0) ?></td>
            <?php endforeach; ?>
            <td><?= Yii::$app->formatter->asDecimal($grandTotal, 0) ?></td>
         </tr>
         </tfoot>
      </table>
   </div>

   <hr>

   <div class="mt-2" id="contract-sum-table">
      <div class="d-flex justify-content-between align-items-center">
         <h3>Сумма договоров по количеству месяцев</h3>
         <button class="btn btn-primary btn-xsmall mb-2"
                 onclick="ExportToExcel('tbl_contract_exporttable_to_xls', 'statistic-contract-sum.xlsx')">
            <?= Yii::$app->params['export_to'][$lang] ?>
         </button>
      </div>
      <table class="table table-sm table-striped table-bordered text-center" border="1" id="tbl_contract_exporttable_to_xls">
         <thead>
         <tr>
            <th style="border: 1px solid #000"></th>
            <th style="border: 1px solid #000" colspan="<?= count($contractMonthCounts) + 1 ?>">Количество месяцев</th>
         </tr>
         <tr>
            <th style="border: 1px solid #000"><?= Yii::$app->params['labels_company'][$lang] ?></th>
            <?php foreach ($contractMonthCounts as $monthCount): ?>
               <th style="border: 1px solid #000"><?= $monthCount ?></th>
            <?php endforeach; ?>
            <th style="border: 1px solid #000">Итого</th>
         </tr>
         </thead>
         <tbody>
         <?php foreach ($contractCompanies as $company): ?>
            <tr class="<?= $company['total'] <= 0 ? 'table-danger' : '' ?>">
               <td style="border: 1px solid #000; text-align: left"><?= Html::encode($company['name']) ?></td>
               <?php foreach ($contractMonthCounts as $monthCount): ?>
                  <td style="border: 1px solid #000">
                     <?= Yii::$app->formatter->asDecimal($company['sums'][$monthCount] ?? 0, 0) ?>
                  </td>
               <?php endforeach; ?>
               <td style="border: 1px solid #000">
                  <?= Yii::$app->formatter->asDecimal($company['total'], 0) ?>
               </td>
            </tr>
         <?php endforeach; ?>
         </tbody>
         <tfoot class="table-dark">
         <tr>
            <td>Итого</td>
            <?php $contractGrandTotal = 0; ?>
            <?php foreach ($contractMonthCounts as $monthCount): ?>
               <?php
               $monthTotal = 0;
               foreach ($contractCompanies as $company) {
                   $monthTotal += $company['sums'][$monthCount] ?? 0;
               }
               $contractGrandTotal += $monthTotal;
               ?>
               <td><?= Yii::$app->formatter->asDecimal($monthTotal, 0) ?></td>
            <?php endforeach; ?>
            <td><?= Yii::$app->formatter->asDecimal($contractGrandTotal, 0) ?></td>
         </tr>
         </tfoot>
      </table>
   </div>

   <hr>

   <div class="mt-2" id="payment-sum-table">
      <div class="d-flex justify-content-between align-items-center">
         <h3>Сумма платежей по количеству месяцев</h3>
         <button class="btn btn-primary btn-xsmall mb-2"
                 onclick="ExportToExcel('tbl_payment_exporttable_to_xls', 'statistic-payment-sum.xlsx')">
            <?= Yii::$app->params['export_to'][$lang] ?>
         </button>
      </div>
      <table class="table table-sm table-striped table-bordered text-center" border="1" id="tbl_payment_exporttable_to_xls">
         <thead>
         <tr>
            <th style="border: 1px solid #000"></th>
            <th style="border: 1px solid #000" colspan="<?= count($paymentMonthCounts) + 1 ?>">Количество месяцев</th>
         </tr>
         <tr>
            <th style="border: 1px solid #000"><?= Yii::$app->params['labels_company'][$lang] ?></th>
            <?php foreach ($paymentMonthCounts as $monthCount): ?>
               <th style="border: 1px solid #000"><?= $monthCount ?></th>
            <?php endforeach; ?>
            <th style="border: 1px solid #000">Итого</th>
         </tr>
         </thead>
         <tbody>
         <?php foreach ($paymentCompanies as $company): ?>
            <tr class="<?= $company['total'] <= 0 ? 'table-danger' : '' ?>">
               <td style="border: 1px solid #000; text-align: left"><?= Html::encode($company['name']) ?></td>
               <?php foreach ($paymentMonthCounts as $monthCount): ?>
                  <td style="border: 1px solid #000">
                     <?= Yii::$app->formatter->asDecimal($company['sums'][$monthCount] ?? 0, 0) ?>
                  </td>
               <?php endforeach; ?>
               <td style="border: 1px solid #000">
                  <?= Yii::$app->formatter->asDecimal($company['total'], 0) ?>
               </td>
            </tr>
         <?php endforeach; ?>
         </tbody>
         <tfoot class="table-dark">
         <tr>
            <td>Итого</td>
            <?php $paymentGrandTotal = 0; ?>
            <?php foreach ($paymentMonthCounts as $monthCount): ?>
               <?php
               $monthTotal = 0;
               foreach ($paymentCompanies as $company) {
                   $monthTotal += $company['sums'][$monthCount] ?? 0;
               }
               $paymentGrandTotal += $monthTotal;
               ?>
               <td><?= Yii::$app->formatter->asDecimal($monthTotal, 0) ?></td>
            <?php endforeach; ?>
            <td><?= Yii::$app->formatter->asDecimal($paymentGrandTotal, 0) ?></td>
         </tr>
         </tfoot>
      </table>
   </div>
</div>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(tableId, filename, type) {
        var elt = document.getElementById(tableId);
        var exportType = type || 'xlsx';
        var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
        XLSX.writeFile(wb, filename || ('statistic-count.' + exportType));
    }
</script>
