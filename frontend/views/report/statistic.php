<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['reports_statistic'][$lang] . ': ' . Yii::$app->formatter->asDate($start, "php:d.m.Y") . ' - ' . Yii::$app->formatter->asDate($end, "php:d.m.Y");
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="report-index">
    <div class="row">
        <div class="col-md-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary  btn-xsmall mb-2"
                    onclick="ExportToExcel('xlsx')"><?= Yii::$app->params['export_to'][$lang] ?></button>
        </div>
    </div>
   
   <?= $this->render('_search_index') ?>


    <div class="mt-2">
        <table class="table table-bordered text-center table-sm" border="1" id="tbl_exporttable_to_xls">
            <thead>
            <tr class="active" style="font-weight: bold;">
                <td style="border: 1px solid #000">#</td>
                <td style="border: 1px solid #000"><?= Yii::$app->params['labels_company'][$lang] ?></td>
                <td style="border: 1px solid #000">Кол. оформ. кредитов</td>
                <td style="border: 1px solid #000">Оформленные кредиты</td>

                <td style="border: 1px solid #000; display: none;">Кутилаетган сумма</td>
                <td style="display: none;"><?= Yii::$app->params['report_total_price'][$lang] ?></td>
                <td style="border: 1px solid #000"><?= Yii::$app->params['credit_payed_amount'][$lang] ?></td>
                <!--<td style="border: 1px solid #000" class="active"><?= Yii::$app->params['todat_index_unpayed'][$lang] ?></td>-->
            </tr>
            </thead>
            <tbody>
            <?php
            $total00 = 0;
            $total0 = 0;
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            ?>
            <?php $m = 1;
            foreach ($company as $com_one): ?>
               <?php
               $credit_summa = \common\models\Credit::find()
                  ->where(['company_id' => $com_one->id])
                  ->andWhere(['!=', 'credit_status', [-2, -1, 5]])
                  ->andWhere(['rejected' => 0])
                  ->andWhere(['between', 'created', $start, $end]);
               ?>

                <tr class="<?php echo ($credit_summa->count() <= 0) ? 'table-danger' : ''; ?>">
                    <td style="border: 1px solid #000"><?= $m; ?></td>
                    <td style="border: 1px solid #000"><?= $com_one->name; ?></td>


                    <td style="border: 1px solid #000">
                       <?= Yii::$app->formatter->asDecimal($credit_summa->count(), 0); ?>
                       <?php $total0 = $total0 + $credit_summa->count(); ?>
                    </td>
                    <td style="border: 1px solid #000; ">
                       <?php $ustama = $credit_summa->sum('doc_total_price') - $credit_summa->sum('self_price');
                       $summ = $credit_summa->sum('self_price') + $ustama
                       ?>
                       <?= Yii::$app->formatter->asDecimal($summ, 0);
                       $total00 = $total00 + $summ;
                       ?>
                    </td>

                    <td style="border: 1px solid #000;display: none;">
                        <!-- сумма кредитов в период-->
                       <?php
                       $credit_plan_summa = \common\models\CreditPlan::find()
                          ->joinWith('credit')
                          ->where(['credit_plan.company_id' => $com_one->id])
                          ->andWhere(['between', 'credit_plan.created', $start, $end])
                          ->andWhere(['!=', 'credit.credit_status', [-2, -1, 5]])
                          ->andWhere(['rejected' => 0])
                          ->sum('pay_summa');
                       ?>
                       <?= Yii::$app->formatter->asDecimal($credit_plan_summa, 0);
                       $total1 = $total1 + $credit_plan_summa; ?>
                    </td>
                    <td style="border: 1px solid #000">
                        <!-- сумма кредитов в период-->
                       <?php
                       $payments_summa = \common\models\Payments::find()
                          ->where(['company_id' => $com_one->id])
                          ->andWhere(['between', 'created', $start, $end])
                          ->sum('amount');
                       ?>
                       <?= Yii::$app->formatter->asDecimal($payments_summa + $credit_summa->sum('prepaid_summa'), 0);
                       $total2 = $total2 + $payments_summa + $credit_summa->sum('prepaid_summa'); ?>
                    </td>
                    <!--
                    <?= Yii::$app->formatter->asDecimal($credit_plan_summa - $payments_summa);
                    $total3 = $total3 + ($credit_plan_summa - $payments_summa); ?></td>
                    -->
                </tr>
               <?php $m++; endforeach; ?>
            </tbody>
            <tfoot class="table-dark">
            <tr>
                <td></td>
                <td></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total0, 0); ?></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total00, 0); ?></td>
                <td style="display: none;"><?php echo Yii::$app->formatter->asDecimal($total1, 0); ?></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total2, 0); ?></td>
                <td style="display: none;"><?php echo Yii::$app->formatter->asDecimal($total3, 0); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>

</div>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
        return dl ?
            XLSX.write(wb, {bookType: type, bookSST: true, type: 'base64'}) :
            XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    }

</script>

