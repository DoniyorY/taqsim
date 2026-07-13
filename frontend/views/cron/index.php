
<table class="table table-bordered">
        <tr>
            <td>Кредит</td>
            <td>Магазин</td>
            <td>Дата оплаты</td>
            <td>Сумма</td>
            <td>Юрист дата</td>
            <td></td>
            <td>Примечание</td>
            <td>СМС</td>
            <td>Статус</td>
        </tr>
    <?php foreach($dataProvider as $one): ?>
        <tr>
            <td><?php echo $one->credit_id; ?></td>
            <td><?php echo $one->company_id; ?></td>
            <td><?php echo date('j.m.Y',$one->created); ?></td>
            <td><?php echo $one->pay_summa; ?></td>
            <td><?php echo date('j.m.Y',$one->yurist_goday); ?></td>
            <td><?php //echo ($one->created-time())/86400; ?></td>
            <td><?php echo $one->content; ?></td>
            <td><?php  if ($one->is_sent_sms == 0) {
                    echo 'Да';
                } else {
                    echo 'Нет';
                }?></td>
            <td><?php echo Yii::$app->params['pay_status'][Yii::$app->language][$one->pay_status]; ?></td>
        </tr>
    <?php endforeach;?>
</table>

