<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CreditPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$lang = Yii::$app->language;
$this->title = Yii::$app->params['credits_plan_late'][$lang];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="credit-plan-index">

    <div class="row">
        <div class="col-sm-6">
            <h1>check -  <?= Html::encode($this->title) ?></h1>
        </div>
        <?php if (Yii::$app->user->identity->role == 0):?>
            <div class="col-sm-4">
                <table class="table table-bordered">
                    <tr>
                        <th class="table-active"><?php echo Yii::$app->params['total_late_summa'][Yii::$app->language]; ?> </th>
                    </tr>
                </table>
            </div>
        <?php endif;?>
        <div class="col-sm-2"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
                $time=time();
                $yurist_count=0;
                $no_count=0;
            ?>
            <?php foreach($allplans as $one): ?>
                <?php
                $den65=$one->created+5616000;
                if($time > $den65) {
                    $credit_plan=\common\models\CreditPlan::findOne($one->id);
                    $credit_plan->yurist_goday=$den65;
                    $credit_plan->pay_status=4;
                    $credit_plan->save();
                    $yurist_count=$yurist_count+1;
                } else {
                    $credit_plan=\common\models\CreditPlan::findOne($one->id);
                    $credit_plan->yurist_goday=$den65;
                    $credit_plan->save();
                    $no_count=$no_count+1;
                }
                ?>
            <?php endforeach;?>
            <?php echo 'no='.$no_count; ?>
            <?php echo '=====';?>
            <?php echo 'toyurist='.$yurist_count; ?>
        </div>
    </div>

</div>
