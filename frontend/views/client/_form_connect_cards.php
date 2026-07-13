<?php
$params=Yii::$app->params;
$lang = Yii::$app->language;

/**
 * @var $model \common\models\ClientCards
 * @var $credits \common\models\Credit
 */
use yii\helpers\Html;
?>
<form action="<?= \yii\helpers\Url::to(['link-card']) ?>" method="post">
    <?=Html::hiddenInput(Yii::$app->request->csrfParam,Yii::$app->request->csrfToken)?>
    <table class="table table-sm table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Номер договора</th>
            <th>Сумма</th>
            <th>Оплачено</th>
            <th>Осталось</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php $i = 1;
        foreach ($credits as $credit):
            $payment = \common\models\Payments::find()->where(['credit_id' => $credit->id])->sum('amount');
            $linked = \common\models\CardCreditLink::findOne(['credit_id' => $credit->id, 'card_id' => $model->id]);
        echo Html::hiddenInput('card_id', $model->id);
            ?>
            <tr>

                <td><?= $i; ?></td>
                <td><?= "Договор №$credit->id" ?></td>
                <td class="table-info"><?= Yii::$app->formatter->asDecimal($credit->doc_total_price, 0) ?></td>
                <td class="table-success"><?= Yii::$app->formatter->asDecimal($payment ?? 0, 0) ?></td>
                <td class="table-primary"><?= Yii::$app->formatter->asDecimal($credit->doc_total_price - (int)$payment) ?></td>
                <td class="text-center">
                    <?=Html::hiddenInput("attach[$credit->id]",'0',['id'=>"cardSwitch$i"])?>
                    <input type="checkbox" name="attach[<?=$credit->id?>]" style="height: 20px; width: 20px;" id="cardSwitch<?= $i ?>" <?=($linked)?"checked":""?> value="1">
                </td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-success w-100"><?=$params['labels_save'][$lang]?></button>
    </div>
</form>