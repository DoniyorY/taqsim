<?php

use yii\helpers\Url;
use yii\helpers\Html;
$lang=Yii::$app->language;
?>

<form action="<?= Url::to(['/analytics/index']) ?>" method="get">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="begin_date">
                    <?=Yii::$app->params['labels_doc_date_start'][$lang]?>
                </label>
                <input type="date" id="begin_date" name="Search[begin_date]" class="form-control" value="<?=date('Y-m-d',$before)?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="end_date">
                    <?=Yii::$app->params['labels_doc_date_end'][$lang]?>
                </label>
                <input type="date" id="end_date" class="form-control" name="Search[end_date]" value="<?=date('Y-m-d',$now)?>">
            </div>
        </div>
        <div class="col-md-2 mt-4">
            <?= Html::a(Yii::$app->params['labels_reset_button'][$lang],['/analytics/main/index'], ['class' => 'btn btn-outline-secondary w-100']) ?>
        </div>
        <div class="col-md-2 mt-4">
            <?= Html::submitButton(Yii::$app->params['header_search_button'][$lang], ['class' => 'btn btn-primary w-100']) ?>
        </div>
    </div>
</form>
