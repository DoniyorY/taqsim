<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditType */
$lang = Yii::$app->language;
$this->title =  Yii::$app->params['update_credit_type'][$lang] .' '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Credit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credit-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
