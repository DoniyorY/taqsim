<?php
$this->title = 'График';
/**
 * @var $model \common\models\Credit
 * @var $items \common\models\CredditItem
 * @var $plan  \common\models\CreditPlan
 */
?>

<style>
    table tr td {
        padding: 5px;
    }

    ul {
    }
</style>
<button class="btn btn-primary" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>

<div id="PrintDoc">
    <p style="text-align: right;clear: both;">
        <?php echo $model->doc_date_start; ?> тузилган №_ <?php echo $model->id; ?>сонли <br/>
        Олди-сотди шартномасига <br/> 1-Сонли Илова.
    </p>
    <h2 class="text-center" style="text-align: center;"><span>Т Ў Л О В</span> <span style="margin-left: 20px;">Г Р А Ф И Г И</span>
    </h2>

    <table style="margin:0 auto; width: 80%;">
        <tr>
            <td style="text-align: left">Шартнома буйича тўловнинг умумий суммаси::</td>
            <td style="text-align: right"><?php echo Yii::$app->formatter->asDecimal($model->doc_total_price, 0); ?>
                сўм.
            </td>
        </tr>
        <tr>
            <td style="text-align: left">Олдиндан тўлов:</td>
            <td style="text-align: right"><?php echo Yii::$app->formatter->asDecimal($model->prepaid_summa, 0); ?>
                сўм.
            </td>
        </tr>
        <tr>
            <td style="text-align: left">Тўловни амалга ошириш муддати, <?php echo $model->month_count; ?> ой:</td>
            <td style="text-align: right">
                <?php
                $nach = \common\models\CreditPlan::find()->where(['credit_id' => $model->id])->orderby('created ASC')->one();
                $kon = \common\models\CreditPlan::find()->where(['credit_id' => $model->id])->orderby('created DESC')->one();
                ?>
                <?php echo date('j.m.Y', $nach->created) ?>
                - <?php echo date('j.m.Y', $kon->created) ?>
            </td>
        </tr>
    </table>

    <br clear="all"/>

    <table style="margin:0 auto;width: 80%;text-align: center" border="1">
        <tr style="font-weight: bold;">
            <td> №</td>
            <td> Тўлов муддати</td>
            <td> Тўлов суммаси</td>
            <td> Қолдиқ</td>
        </tr>
        <?php $i = 1;
        $ostatok = $model->doc_total_price;
        foreach ($plan as $item): ?>
            <tr>
                <td> <?php echo $i; ?></td>
                <td> <?php echo date('j.m.Y', $item->created) ?></td>
                <td> <?php echo Yii::$app->formatter->asDecimal($item->pay_summa, 0); ?> </td>
                <td> <?php
                    $ostatok = $ostatok - $item->pay_summa;
                    echo Yii::$app->formatter->asDecimal($ostatok, 0); ?></td>
            </tr>
            <?php $i++;

        endforeach; ?>
    </table>

    <br clear="all"/>
    <table style="width: 80%; text-align: center; margin: 0 auto;">
        <tr>
            <td style="text-align: center;width:45%;"><p><strong>Сотувчи:</strong></p></td>
            <td style="text-align: center;width:150px;"></td>
            <td style="text-align: center;width:45%;"><p><strong>Сотиб олувчи:</strong></p></td>
        </tr>
        <tr>
            <td style="text-align: left;width:53%;">
                <?php
                echo $model->company->company_title;
                echo $model->company->company_props;
                echo 'Директор: '. $model->company->company_director;
                ?>
            </td>
            <td>
                
            </td>
            <td style="text-align: left;width:53%;">
                <p>
                    <strong>Фуқаро:</strong> <?php echo $model->client->fullname; ?><br/>
                    <strong>Яшаш манзили:</strong> <?php echo $model->client->address; ?><br/>
                    <strong>Паспорт маълумотлари:</strong> <?php echo $model->client->passport_numb; ?>
                    , <?php echo $model->client->passport_enddate//Yii::$app->formatter->asDate($model->client->passport_enddate, "php:d.m.Y"); ?>,
                    <?php echo $model->client->passport_whose; ?> томонидан берилган <br/>
                    <strong>Tелефон:</strong>T<?php echo $model->client->phone; ?>
                </p>
                <br clear="all"/>
                <strong><?php echo $model->client->fullname; ?>: <img src="<?= $sign->client_sign ?>"
                                                                      style="height: 150px;" alt=""> </strong><br/>
            </td>
        </tr>
    </table>
</div>
