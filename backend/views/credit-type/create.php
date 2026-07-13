<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditType */

$this->title = 'Create Credit Type';
$this->params['breadcrumbs'][] = ['label' => 'Credit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
