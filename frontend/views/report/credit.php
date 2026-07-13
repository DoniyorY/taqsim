<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$lang = Yii::$app->language;
$this->title = $title . ' ' . Yii::$app->formatter->asDatetime($start, "php:d.m.Y") . ' - ' . Yii::$app->formatter->asDatetime($end, "php:d.m.Y");
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
        <div class="col-md-12">
            <?php \yii\widgets\ActiveForm::begin(['method' => 'post', 'action' => \yii\helpers\Url::to(['/report/credit'])]) ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <?= Html::label('Дата начала') ?>
                        <?= Html::input('date', 'start', '', ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?= Html::label('Дата начала') ?>
                        <?= Html::input('date', 'end', '', ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?= Html::label('Магазин') ?>
                        <?= Html::dropDownList('company', '', ArrayHelper::map(\common\models\Company::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => '']) ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <?= Html::submitButton('Поиск', ['class' => 'btn mt-4 btn-primary w-100']) ?>
                </div>
                <div class="col-md-1">
                    <?= Html::a('Сбросить', ['/report/credit'], ['class' => 'btn mt-4 btn-warning w-100']) ?>
                </div>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>

    <div class="mt-2">
        <table class="table table-bordered text-center table-sm" id="tbl_exporttable_to_xls_1">
            <tr class="active" style="font-weight: bold;">
                <th>#</th>
                <th><?= Yii::$app->params['labels_clients'][$lang] ?></th>
                <th><?= Yii::$app->params['labels_phone'][$lang] ?></th>
                <th><?= Yii::$app->params['labels_credit_id'][$lang] ?></th>
                <th><?= Yii::$app->params['labels_company'][$lang] ?></th>
                <th><?= Yii::$app->params['labels_user'][$lang] ?></th>
                <th><?= Yii::$app->params['labels_self_price'][$lang] ?></th>
                <th><?= Yii::$app->params['30%prepaid'][$lang] ?></th>
                <th><?= Yii::$app->params['credit_payed_amount'][$lang] ?></th>
                <th class="active"><?= Yii::$app->params['todat_index_unpayed'][$lang] ?></th>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            ?>
            <?php $i = 1;
            foreach ($credits as $n => $item): ?>
                <tr class="main-table">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item['fullname']; ?></td>
                    <td><?php echo $item['phone']; ?></td>
                    <td><?php echo $item['id']; ?> - <?php echo $item['doc_date_start']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo (isset($item['username'])) ? $item['username'] : '-'; ?></td>
                    <td><?php echo yii::$app->formatter->asDecimal($item['real_summa'], 0);
                        $total1 = $total1 + $item['real_summa']; ?></td>
                    <td><?php echo yii::$app->formatter->asDecimal($item['prepaid_summa'], 0);
                        $total2 = $total2 + $item['prepaid_summa']; ?></td>
                    <td id="psumma<?= $item['id'] ?>">
                        0
                        <?php /*echo yii::$app->formatter->asdecimal($item['psumma']); */ ?><!--
                        --><?php /*$total3 = $total3 + $item['psumma']; */ ?>
                    </td>
                    <td class="ost" id="ost<?= $item['id']; ?>">
                        <?= Yii::$app->formatter->asDecimal($item['real_summa']); ?>
                        <!--                        <?php /*$res_numb = $item['ost']; */ ?>
                        <?php /*echo yii::$app->formatter->asDecimal($res_numb); */ ?>
                        --><?php
                        /*                        $total4 = $total4 + $res_numb;
                                                */ ?>
                    </td>
                </tr>

                <?php $i++; endforeach; ?>

            <tfoot class="table-dark">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo yii::$app->formatter->asDecimal($total1, 0); ?></td>
                <td><?php echo yii::$app->formatter->asDecimal($total2, 0); ?></td>
                <td id="totalPayed"><?php echo yii::$app->formatter->asDecimal($total3, 0); ?></td>
                <td id="totalOst"><?php echo yii::$app->formatter->asDecimal($total4, 0); ?></td>

            </tr>
            </tfoot>
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

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    function setPsumma() {
        let totalPayed = 0;
        let totalOst = 0;
        <?php foreach ($payments as $item):?>
        document.querySelector(`#psumma<?=$item['id'];?>`).textContent = numberWithCommas('<?=$item['psumma']?>')

        document.querySelector(`#ost<?=$item['id'];?>`).textContent = numberWithCommas('<?=$item['ost']?>')

        totalPayed += parseInt("<?=$item['psumma']?>");
        totalOst += parseInt("<?=$item['ost']?>");
        <?php endforeach;?>
        document.querySelector('#totalPayed').textContent = numberWithCommas(totalPayed);
        let total_ost = 0;
        document.querySelectorAll('.main-table .ost').forEach(element => {
            console.log(element.textContent, +(element.textContent.replaceAll(' ', '').replaceAll(',','.')));
            total_ost += parseFloat(element.textContent.replaceAll(' ', '').replaceAll(',','.'))
        });
        document.querySelector('#totalOst').textContent = numberWithCommas(totalOst);
    }

    setPsumma();

</script>
