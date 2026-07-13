<?php

use yii\helpers\Html;


$lang = Yii::$app->language;
/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = Yii::$app->params['update_region'][$lang] .' '. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="region-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
