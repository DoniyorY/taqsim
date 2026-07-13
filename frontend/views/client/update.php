<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Client */

$this->title = Yii::$app->params['update'][Yii::$app->language] .': ' . $model->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['labels_client'][Yii::$app->language].'ы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::$app->params['update'][Yii::$app->language];
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
