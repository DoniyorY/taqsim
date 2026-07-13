<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['labels_users'][Yii::$app->language];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="row">
        <div class="col-md-6">
            <h1 style="margin: 0;"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <p style="margin: 0;">
                <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'prevPageLabel' => '<span class="page-item">Пред</span>',
            'nextPageLabel' => '<span class="page-item">След</span>',
            'disabledPageCssClass' => 'page-link',
            'activePageCssClass' => 'page-item active',
            'maxButtonCount' => 5,
            'linkOptions' => ['class' => 'page-link'],
            'options' => [
                'tag' => 'ul',
                'class' => 'pagination',
                'style' => 'margin-left: 1px;'
            ],
        ],
        'filterModel' => $searchModel,
        'rowOptions' => function ($data) {
            if ($data->status == 9): return ['class' => 'table-danger'];elseif ($data->status  == 10): return ['class' => 'table-success']; endif;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'email:email',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',

            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return Yii::$app->params['user_status'][Yii::$app->language][$data->status];
                },
                'filter'=>[10=>'Активный',9=>'Отключенный']
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
            [
                'attribute' => 'role',
                'value' => function ($data) {
                    return Yii::$app->params['user_roles'][Yii::$app->language][$data->role];
                },
                'filter' => Yii::$app->params['user_roles'][Yii::$app->language],
            ],
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{Inactive} {Active} - {view} - {delete}',
                'buttons' => [
                    'Inactive' => function ($url, $model) {

                        $url = Url::to(['user/status', 'id' => $model->id, 'status' => 9]);
                        if ($model->status == 10):
                            return Html::a('Блокировать', $url, [
                                'title' => 'Блокировать',
                                'class' => 'btn btn-danger btn-sm',
                                'data-confirm' => Yii::t('yii', 'Вы дейтвительно хотите блокировать?'),
                                'data-method' => 'post',
                            ]);
                        endif;
                    },
                    'Active' => function ($url, $model) {

                        $url = Url::to(['user/status', 'id' => $model->id, 'status' => 10]);
                        if ($model->status == 9):
                            return Html::a('Активировать', $url, [
                                'title' => 'Активировать',
                                'class' => 'btn btn-success btn-sm',
                                'data-confirm' => Yii::t('yii', 'Вы дейтвительно хотите активизировать?'),
                                'data-method' => 'post',
                            ]);
                        endif;
                    },

                ],
            ],
        ],
    ]); ?>


</div>
