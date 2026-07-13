<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
$lang = Yii::$app->language;
$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['labels_users'][$lang], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="user-view">
    <div class="row">

        <div class="col-md-6">
            <h3 style="margin: 0;">Пользователь: <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6 text-right">
            <p>
                <?= Html::a(Yii::$app->params['update'][$lang], ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::$app->params['delete'][$lang], ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>

        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th class="table-active">Логин: </th>
                    <td><?php echo $model->username;  ?></td>

                    <th class="table-active">Почта: </th>
                    <td><?php echo $model->email;  ?></td>

                    <th class="table-active">Статус: </th>
                    <td><?php echo Yii::$app->params['user_status'][Yii::$app->language][$model->status];;  ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <h3><?= Html::encode('Доступы сотрудника') ?></h3>
            <?= $this->render('user_menu_form', [
                'model' => $userMenu,
                'items' => $items,
                'result' => $result,
            ]) ?>
        </div>
    </div>

</div>
