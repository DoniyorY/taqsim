<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserMenuItem */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
?>

<div class="user-menu-item-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row mt-3">
        <?php foreach ($items as $item): ?>
            <?php
            if (!empty($result->link)) {

                $links = explode(',', $result->link);
            } else {
                $links = [];
            }
            if (($key = array_search($item->id, $links)) !== false):
                ?>
                <div class="col-md-3">
                    <?Php
                    if ($item->{"content_$lang"} === ' #') {
                        echo $form->field($model, 'links[]')->checkbox(['value' => $item->id, 'checked' => true, 'hidden' => true])->label(false);
                    } else {
                        echo $form->field($model, 'links[]')->checkbox(['value' => $item->id, 'checked' => true])->label($item->{"content_$lang"});
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="col-md-3">
                    <?Php if ($item->{"content_$lang"} === ' #'){
                        echo $form->field($model, 'links[]')->checkbox(['value' => $item->id, 'hidden' => true])->label(false);
                    }else{
                        echo $form->field($model, 'links[]')->checkbox(['value' => $item->id])->label($item->{"content_$lang"});
                    } ?>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::$app->params['labels_save'][Yii::$app->language], ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
