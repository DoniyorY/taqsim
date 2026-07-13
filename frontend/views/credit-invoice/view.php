<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CreditInvoice */

$this->title = 'ЮК ХАТИ / ХИСОБВАРАК-ФАКТУРА №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Счет-фактуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$credit_items = \common\models\CreditItem::findAll(['credit_id' => $model->credit_id]);
?>

<style>
    table tr td {
        padding: 7px;
    }

    ul {
    }
</style>

<div class="faktura-view">

    <div class="container">

        <button class="btn btn-primary btn-sm float-right" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>
        <div id="PrintDoc" style="font-size:11px;">
            <h4 align="center"><?= Html::encode($this->title) ?></h4>
            <p align="center" style="font-size:10px;">
                <?php echo $model->credit->doc_date_start; ?> йилдаги № <?php echo $model->credit->id; ?> - сонли
                муддатли
                тулов шарти билан тузилган олди - сотди шартномасига асосан
            </p>
            <table style="width: 100%;font-size:11px;  border-collapse: collapse;" border="1">
                <tr style="font-weight: bold;text-align: center;">
                    <td style="width: 48%;">" СОТУВЧИ "</td>
                    <td style="width: 48%;">" ХАРИДОР "</td>
                </tr>
                <tr>
                    <td style="padding: 10px;">
                        <h3 style="margin:0 !important;"><?= $model->credit->company->company_title ?></h3>

                        <?php echo $model->credit->company->company_props; ?>
                        <?= 'Директор: ' . $model->credit->company->company_director ?>
                        <br/>
                        <br/>
                        М.У
                    </td>
                    <td style="padding: 10px;">
                        <p>
                            <strong>Фуқаро:</strong> <?php echo $model->credit->client->fullname; ?><br/>
                            <strong>Яшаш манзили:</strong> <?php echo $model->credit->client->address; ?><br/>
                            <strong>Паспорт маълумотлари:</strong> <?php echo $model->credit->client->passport_numb; ?>,
                            <?php echo $model->credit->client->passport_enddate ?>
                            <br/>
                            <?php echo $model->credit->client->passport_whose; ?> томонидан берилган <br/>
                            <strong>Tелефон:</strong> <?php echo $model->credit->client->phone; ?>
                        </p>
                    </td>
                </tr>
            </table>
            <br/>
            <table style="width: 100%;text-align: center; border-collapse: collapse;" border="1" >
                <tr style="font-weight: bold;">
                    <td>Т / р</td>
                    <td>Махсулот номи</td>
                    <td>Улчов бирлиги</td>
                    <td>Сони</td>
                    <td>Махсулотнинг нархи</td>
                    <td>Умумий киймати</td>
                </tr>
                <?php $i = 1;
                $total = 0;
                $percent = $model->credit->percent;
                foreach ($credit_items as $item): ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $item->title; ?></td>
                        <td>Дона</td>
                        <td><?php echo $item->count; ?></td>
                        <td>
                            <?php
                            $item_summa = ($item->summa * $percent / 100) + $item->summa;
                            ?>
                            <?php echo Yii::$app->formatter->asDecimal($item_summa, 0); ?>

                        </td>
                        <td>  <?php echo Yii::$app->formatter->asDecimal($item_summa * $item->count, 0); ?></td>
                    </tr>
                    <?php $i++;
                    $total = $total + ($item_summa * $item->count);
                endforeach; ?>
                <tr style="font-weight: bold;">
                    <td></td>
                    <td>Жами</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo Yii::$app->formatter->asDecimal($total, 0); ?></td>
                </tr>
            </table>
            <p style="font-weight: bold;font-size: 12px;">
                Жами махсулот cуммаси: <i>( <?php echo $model->credit->doc_total_text; ?> ) сум</i>
            </p>


            <table style="width: 100%;text-align: left;font-size:11px; border-collapse: collapse;" border="0">
                <tr style="text-align: left;font-weight: bold;">
                    <td> Топширдим:</td>
                    <td style="position: relative; height:50px;">
                        Олдим:<br/>
                        <img src="<?=$sign->client_sign?>" style="position:absolute;zoom:2;top:-10px;left:40px;width: 100px" alt="">
                    </td>
                </tr>
                <tr>
                    <td> Рахбар ___________________ Ф.Мирсодиков</td>
                    <td></td>
                </tr>
                <tr>
                    <td> М.У.</td>
                    <td><small>Ишончнома буйича</small></td>
                </tr>
                <tr>
                    <td>Махсулот(лар)ни бериб юубордим_____________</td>
                    <td>(ФИО) ____________________________________</td>
                </tr>
            </table>

        </div>


    </div>
</div>
<br clear="all"/><br clear="all"/><br clear="all"/><br clear="all"/><br clear="all"/>

