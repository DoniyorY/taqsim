<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['labels_user'][Yii::$app->language], 'url' => ['index']];
$this->title = 'Регистрация нового пользователя';
?>
<div class="user-create">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_signup_form', [
        'model' => $model,
    ]) ?>

</div>
