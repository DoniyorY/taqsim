<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\SettingsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::$app->params['labels_settings'][Yii::$app->language];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">
    <div class="row">
        <div class="col-md-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
   
   <?php  echo $this->render('_form', ['model' => new Settings()]); ?>
   
   <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
         ['class' => 'yii\grid\SerialColumn'],
         
         //'id',
         'name',
         [
            'attribute' => 'company_id',
            'value' => function ($model) {
               if ($model->company)
                  return $model->company->name;
            },
            'format'=>'raw',
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Company::find()->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
         ],
         'value',
         [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, $model, $key, $index, $column) {
               return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template' => '{update}'
         ],
      ],
   ]); ?>


</div>
