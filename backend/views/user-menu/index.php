<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => new \common\models\UserMenu(),]) ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'category',
                'value' => function ($data) {
                    return Yii::$app->params['link_category'][Yii::$app->language][$data->category];
                }
            ],
            'link',
            'content_ru:ntext',
            'content_uz:ntext',
            'prior',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{update}-{delete}',
            ],
        ],
    ]); ?>


</div>
