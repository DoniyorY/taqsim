<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMenu */

$this->title = 'Update User Menu: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
