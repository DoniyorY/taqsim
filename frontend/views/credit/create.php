<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Credit */

$this->title = 'Составление кредита';
$this->params['breadcrumbs'][] = ['label' => 'Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-create">

    <h2><?= Html::encode($this->title . ' - Клиент: ' . $client->fullname) ?></h2>
    <hr>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
