<?php
use yii\helpers\Url;
?>
<div class="container">
    <form action="<?=Url::to(['/sign/search'])?>" method="get">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <input type="number" name="credit" class="form-control" placeholder="<?=Yii::$app->params['sign_doc_number'][Yii::$app->language]?>">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-block">Поиск</button>
            </div>
        </div>
    </form>
</div>