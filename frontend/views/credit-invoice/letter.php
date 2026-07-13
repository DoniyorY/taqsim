<?php
$this->title = 'Письмо поручителья';

$this->params['breadcrumbs'][] = ['label' => 'Все кредиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id.' '.$model->doc_date_start, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $model \common\models\Credit
 * @var $items \common\models\CreditItem
 */
?>

<style>
    table tr td {padding:5px;}
    ul { }
</style>
<button class="btn btn-primary  btn-xsmall" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>

<div id="PrintDoc">

    <p style="float: right;">
        « <?php echo $model->company->name; ?> »га<br/>
        « КАФОЛАТЧИ »  <strong><?php if (isset($model->guarantor_id)) echo $model->guarantor->fullname; ?></strong> дан<br/>
        кафолат хати.

    </p>

    <br clear="all"/><br clear="all"/>
    <h2 class="text-center" style="text-align: center;">КАФОЛАТ ХАТИ</h2>
    <p style="text-indent: 30px;text-align: justify;">
        Кафолат хатимнинг кискача мазмуни шундан иборатки агар

        Фуқаро: <strong><?php echo $model->client->fullname; ?></strong>,
        Яшаш манзили: <strong><?php echo $model->client->address; ?></strong>
        Паспорт маълумотлари: <strong><?php echo $model->client->passport_numb; ?></strong>, тугаш санаси:  <strong><?php echo $model->client->passport_enddate; ?></strong>
        <strong><?php echo $model->client->passport_whose; ?></strong> томонидан берилган, телефон: <strong><?php echo $model->client->phone; ?></strong>

        <strong>№<?php echo $model->id; ?>, <?php echo $model->doc_date_start; ?>
            <?php if (isset($model->guarantor_id)):?>
        </strong> шартномада курсатилган
        туловларни уз вахтида амалга оширилмаса мен

        <strong><?php echo $model->guarantor->fullname; ?></strong>, яшаш манзили
        <strong><?php echo $model->guarantor->address; ?></strong> да яшовчи, пасспорт маълумотлари
        <strong><?php echo $model->guarantor->passport_numb; ?></strong> , тугаш санаси: <strong><?php echo Yii::$app->formatter->asDate($model->guarantor->passport_enddate,"php:d.m.Y"); ?></strong>
        <strong><?php echo $model->guarantor->passport_whose; ?></strong>  томонидан берилган, телефон

        <?php echo $model->guarantor->phone; ?> ,

        тулаб беришни уз зиммамга оламан.
        <br/>
        <br/>

    <table style="width: 70%;">
        <tr> <td>  Ф.И.О:  </td> <td> <?php echo $model->guarantor->fullname; ?> </td> </tr>
        <tr> <td>  ПАСПОРТ МАЪЛУМОТЛАРИ: </td> <td> <?php echo $model->guarantor->passport_numb; ?>,
                <?php echo Yii::$app->formatter->asDate($model->guarantor->passport_enddate,"php:d.m.Y"); ?>,
                <?php echo $model->guarantor->passport_whose; ?> </td> </tr>
        <tr> <td>  ЯШАШ МАНЗИЛИ:</td> <td><?php echo $model->guarantor->address; ?> </td> </tr>
        <tr> <td>  ТЕЛЕФОН: </td> <td> <?php echo $model->guarantor->phone; ?> </td> </tr>
    </table>
    <?php endif;?>
    </p>

    <br clear="all"/><br clear="all"/>

    <table style="width: 100%;" border="0">
        <tr>
            <td style="text-align: left">
                КАФОЛАТ ХАТИ БИЛАН ТАНИШИБ ЧИКДИМ    ________________
            </td>
            <td style="text-align: left"> <?php echo date('j.m.Y йил',time()); ?></td>
        </tr>
    </table>


</div>