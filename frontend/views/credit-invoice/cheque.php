
<?php
$this->title = 'Распечатка чека';
$this->params['breadcrumbs'][] = ['label' => 'Управление кассой', 'url' => ['payments/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>
        <hr/>
    </div>

    <div id="PrintDoc" class="col-md-12">

        <h4 align="center"><?=$model->company->company_title?></h4>
        <table style="width: 100%;">
            <tr><td>Дата:</td><td><?php echo date('j.m.Y H:i',$model->created); ?></td></tr>
            <tr><td>Сумма:</td><td><?php echo Yii::$app->formatter->asDecimal($model->amount, 0); ?> сумов</td></tr>
            <tr><td>Вид оплаты:</td><td><?php echo Yii::$app->params['method'][Yii::$app->language][$model->method_id] ?></td></tr>
        </table>
        <p style="font-size:11px;">
            <?php echo $model->content; ?>
        </p>

    </div>

</div>