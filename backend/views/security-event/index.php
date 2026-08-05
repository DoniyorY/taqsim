<?php

use common\models\SecurityEvent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\SecurityEventSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Security Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="security-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Security Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at:datetime',
            'request_id',
            'event_type',
            'rule',
            'severity',
            'action',
            'ip',
            'method',
            'url:ntext',
            'user_id',
            'user_agent:ntext',
            //'matched_value:ntext',
            //'payload:ntext',
            //'payload_hash',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SecurityEvent $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
