<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMenuItem */

$this->title = 'Create User Menu Item';
$this->params['breadcrumbs'][] = ['label' => 'User Menu Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-menu-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
