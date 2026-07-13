<?php
$lang = Yii::$app->language;

use yii\helpers\Html; ?>
<div class="statistic-count">
   <div class="row">
      <div class="col-md-6">
         <h1><?= Html::encode($this->title) ?></h1>
      </div>
      <div class="col-md-6 text-right">
         <button class="btn btn-primary  btn-xsmall mb-2"
                 onclick="ExportToExcel('xlsx')"><?= Yii::$app->params['export_to'][$lang] ?></button>
      </div>
      <hr>
      <table class="table table-sm table-striped table-bordered text-center">
         <thead>
         <tr>
         
         </tr>
         </thead>
         <tbody>
         <tr>
         
         </tr>
         </tbody>
      </table>
   </div>
</div>
