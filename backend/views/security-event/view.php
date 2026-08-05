<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\SecurityEvent $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Security Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="security-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
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
            'matched_value:ntext',
            'payload:ntext',
            'payload_hash',
        ],
    ]) ?>

</div>
