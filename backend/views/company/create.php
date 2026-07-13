<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['labels_create'][$lang];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['labels_company'][$lang], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>


</div>
