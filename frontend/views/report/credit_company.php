<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title ='Дуконлар буйича хисобот: ' . Yii::$app->formatter->asDatetime($start, "php:d.m.Y").' - '. Yii::$app->formatter->asDatetime($end, "php:d.m.Y");
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="report-index">
    <div class="row">
        <div class="col-md-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary  btn-xsmall mb-2" onclick="ExportToExcel('xlsx')"><?=Yii::$app->params['export_to'][$lang]?></button>
        </div>
    </div>

    <?= $this->render('_search_company') ?>



    <div class="mt-2">
        <table class="table table-bordered text-center table-sm" id="tbl_exporttable_to_xls_1">
            <tr class="active" style="font-weight: bold;">
                <td>#</td>
                <td><?=Yii::$app->params['labels_clients'][$lang]?></td>
                <td><?=Yii::$app->params['labels_phone'][$lang]?></td>
                <td><?=Yii::$app->params['labels_credit_id'][$lang]?></td>
                <td><?=Yii::$app->params['labels_company'][$lang]?></td>
                <td><?=Yii::$app->params['labels_user'][$lang]?></td>
                <td><?=Yii::$app->params['labels_self_price'][$lang]?></td>
                <td><?=Yii::$app->params['30%prepaid'][$lang]?></td>
                <td><?=Yii::$app->params['credit_payed_amount'][$lang]?></td>
                <td class="active"><?=Yii::$app->params['todat_index_unpayed'][$lang]?></td>
            </tr>

            <?php
            $total_summa_real=0;
            $total_ust=0;
            $total_avans=0;
            $total_summa=0;
            $total_psumma=0;
            $total_ost=0;
            ?>
            <?php $i=1; foreach($result as $n=>$item): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item['fullname']; ?></td>
                    <td><?php echo $item['phone']; ?></td>
                    <td><?php echo $item['id']; ?> / <?php echo $item['doc_date_start']; ?> </td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['username']; ?></td>
                    <td><?php echo Yii::$app->formatter->asDecimal($item['real_summa'], 0); ?>
                        <?php $total_summa_real=$total_summa_real+$item['real_summa']; ?></td>
                    <td><?php echo Yii::$app->formatter->asDecimal($item['prepaid_summa'], 0); ?>
                        <?php $total_avans=$total_avans+$item['prepaid_summa']; ?>  </td>
                    <td><?php echo Yii::$app->formatter->asDecimal($item['psumma'], 0); ?>
                        <?php $total_psumma=$total_psumma+$item['psumma']; ?> </td>
                    <td class="danger"><?php echo Yii::$app->formatter->asDecimal($item['ost'], 0); ?>
                        <?php $total_ost=$total_ost+$item['ost']; ?> </td>
                </tr>
                <?php $i++; endforeach; ?>
            <tr class="active" style="font-weight: bold;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total_summa_real, 0); ?></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total_avans, 0); ?></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total_psumma, 0); ?></td>
                <td><?php echo Yii::$app->formatter->asDecimal($total_ost, 0); ?></td>
            </tr>
        </table>

    </div>

</div>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls_1');
        var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
        return dl ?
            XLSX.write(wb, {bookType: type, bookSST: true, type: 'base64'}) :
            XLSX.writeFile(wb, fn || ('<?=Html::encode($this->title)?>.' + (type || 'xlsx')));
    }

</script>
