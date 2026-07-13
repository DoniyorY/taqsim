<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['new_client'][$lang];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['labels_clients'][$lang], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
