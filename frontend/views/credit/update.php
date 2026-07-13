<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Credit */
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
