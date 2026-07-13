<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = 'Хисобот дуконлар буйича: ' . Yii::$app->formatter->asDatetime($start, "php:d.m.Y") . ' - ' . Yii::$app->formatter->asDatetime($end, "php:d.m.Y");
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
            <?php \yii\widgets\ActiveForm::begin(['method' => 'get']) ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <?=Html::label('Дата начала')?>
                        <?=Html::input('date','start','',['class'=>'form-control'])?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?=Html::label('Дата начала')?>
                        <?=Html::input('date','end','',['class'=>'form-control'])?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?=Html::label('Магазин')?>
                        <?=Html::dropDownList('company','',ArrayHelper::map(\common\models\Company::find()->all(),'id','name'),['class'=>'form-control', 'prompt'=>''])?>
                    </div>
                </div>
                <div class="col-md-1">
                    <?=Html::submitButton('Поиск',['class'=>'btn mt-4 btn-primary w-100'])?>
                </div>
                <div class="col-md-1">
                    <?=Html::a('Сбросить',['/report/company-index'],['class'=>'btn mt-4 btn-warning w-100'])?>
                </div>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>

    <div class="mt-2">
        <table class="table table-bordered text-center table-sm" id="tbl_exporttable_to_xls_1">
            <tr class="active" style="font-weight: bold;">
                <td>#</td>
                <td><?= Yii::$app->params['labels_clients'][$lang] ?></td>
                <td><?= Yii::$app->params['labels_phone'][$lang] ?></td>
                <td><?= Yii::$app->params['labels_credit_id'][$lang] ?></td>
                <td><?= Yii::$app->params['labels_company'][$lang] ?></td>
                <td><?= Yii::$app->params['labels_user'][$lang] ?></td>
                <td><?= Yii::$app->params['labels_self_price'][$lang] ?></td>
                <td><?= Yii::$app->params['30%prepaid'][$lang] ?></td>
                <td><?= Yii::$app->params['credit_payed_amount'][$lang] ?></td>
                <td class="active"><?= Yii::$app->params['todat_index_unpayed'][$lang] ?></td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            ?>
            <?php $k = 1;
            foreach ($credits as $one): ?>
                <tr>
                    <td><?php echo $k; ?></td>
                    <td><?php echo $one->client->fullname; ?></td>
                    <td><?php echo $one->client->phone; ?></td>
                    <td><?php echo $one->id; ?> - <?php echo $one->doc_date_start; ?></td>
                    <td><?php echo $one->company->name; ?></td>
                    <td><?php echo (isset($one->user->username))?$one->user->username:'-'; ?></td>
                    <td><?php echo yii::$app->formatter->asDecimal($one->self_price, 0);
                        $total1 = $total1 + $one->self_price; ?></td>
                    <td><?php echo yii::$app->formatter->asDecimal($one->prepaid_summa, 0);
                        $total2 = $total2 + $one->prepaid_summa; ?></td>
                    <td>
                        <?php $pay_all = \common\models\Payments::find()
                            ->joinWith('credit')
                            ->where(['credit_id' => $one->id])
                            ->andWhere(['between','payments.created',$start,$end])
                            ->andFilterWhere(['<>', 'credit.credit_status', -2])
                            ->sum('amount'); ?>
                        <?php echo yii::$app->formatter->asDecimal($pay_all); ?>
                        <?php $total3 = $total3 + $pay_all; ?>
                    </td>
                    <td>
                        <?php $res_numb = $one->self_price - $one->prepaid_summa - $pay_all; ?>
                        <?php echo yii::$app->formatter->asDecimal($res_numb); ?>
                        <?php
                        $total4 = $total4 + $res_numb;
                        ?>
                    </td>
                </tr>
                <?php $k++; endforeach; ?>
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
                <td><?php echo yii::$app->formatter->asDecimal($total3, 0); ?></td>
                <td><?php echo yii::$app->formatter->asDecimal($total4, 0); ?></td>

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

</script>
