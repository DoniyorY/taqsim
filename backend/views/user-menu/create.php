<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMenu */

$this->title = 'Create User Menu';
$this->params['breadcrumbs'][] = ['label' => 'User Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
