<?php
    $lang=yii::$app->language;
?>
<tr>
    <th class="table-info"><?php echo yii::$app->params['report_common'][$lang] ?>:</th>
    <th class="table-active"><i class="fa fa-money" aria-hidden="true"></i> <?php echo yii::$app->params['report_cash'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($total_cash, 0)?></td>
    <th class="table-active"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <?php echo yii::$app->params['report_card'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($total_card, 0)?></td>
    <th class="table-active"><?php echo yii::$app->params['itogo'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($total_cash+$total_card, 0)?></td>
    <td style="width: 150px;"> - </td>
    <th class="table-warning"><?php echo yii::$app->params['today'][$lang] ?>:</th>
    <th class="table-active"><i class="fa fa-money" aria-hidden="true"></i> <?php echo yii::$app->params['report_cash'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($today_cash, 0)?></td>
    <th class="table-active"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <?php echo yii::$app->params['report_card'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($today_card, 0)?></td>
    <th class="table-active"><?php echo yii::$app->params['itogo'][$lang] ?></th>
    <td><?=Yii::$app->formatter->asDecimal($today_cash+$today_card, 0)?></td>
</tr>