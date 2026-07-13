<?php
$lang = Yii::$app->language;
?>

<div>
    <table class="table table-bordered table-sm text-left">
        <tr>
            <th style="width: 200px;">
                <?= Yii::$app->params['labels_fullname'][$lang] ?>
            </th>
            <td>
                <?= $item->client->fullname ?>
            </td>
        </tr>
        <tr>
            <th>
                <?= Yii::$app->params['labels_address'][$lang] ?>
            </th>
            <td>
                <?= $item->client->address ?>
            </td>
        </tr>
        <tr>
            <th>
                <?= Yii::$app->params['labels_passport'][$lang] ?>
            </th>
            <td>
                <?= $item->client->passport_numb ?>
            </td>
        </tr>
        <tr>
            <th>
                <?= Yii::$app->params['labels_extra_phone'][$lang] ?>
            </th>
            <td>
                <?php

                foreach ($extra_phone as $phone) {
                    if ($phone->client_id == $item->client_id) {
                        echo $phone->numb . ' (' . $phone->content . '); <br>';
                    }
                } ?>
            </td>
        </tr>
    </table>
</div>