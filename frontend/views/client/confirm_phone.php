<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>

<div class="container">
    <form action="<?= Url::to(['confirm-phone', 'session_id' => $s_id, 'client_id' => $cl_id, 'card_id' => $c_id,'branch_id'=>$branch_id]) ?>"
          method="post">
        <input hidden="hidden" type="text" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>">
        <div class="row">
            <div class="col-md-8 form-group">
                <label for="phone">Смс Код</label>
                <input type="text" name="confirm_code" class="form-control">
            </div>
            <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-success w-100">Подтвердить</button>
            </div>
        </div>
    </form>
</div>
