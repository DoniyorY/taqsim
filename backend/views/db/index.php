<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'База данных';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <p class="float-right">
        <?= Html::a('Создать дамп БД (экспорт)', ['export'], ['class' => 'btn btn-success']) ?>
    </p>
    <h1><?= Html::encode($this->title) ?></h1>
    <br/>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'dump',
                'format' => 'text',
                'label' => 'Путь к дампу БД',
            ],
            [
                'value' => function ($data) {
                    return '<a class="btn btn-info" href="https://taqsimsavdo.uz/db/' . stristr($data['dump'], 'dump') . '">Скачать</a>';
                },
                'format' => 'html',
                'label' => 'Скачать как файл',
            ],
            /*[
                'format' => 'raw',
                'value' => function ($data, $id) {
                    return Html::a('Импортировать в БД',
                        \yii\helpers\Url::to(['db/import', 'path' => $data['dump']]),
                        ['title' => 'Импортировать', 'class' => 'btn btn-primary']);
                }
            ],*/
            [
                'format' => 'raw',
                //кнопку удаления выводим только если >1 дампа БД
                'value' => function ($data, $id) {
                    if (Yii::$app->params['count_db'] > 1) {
                        return Html::a('Удалить', \yii\helpers\Url::to(['db/delete', 'path' => $data['dump']]), ['title' => 'Удалить', 'class' => 'btn btn-danger']);
                    } else return false;
                }
            ],
        ],
    ]); ?>

</div>