<?php
$lang = Yii::$app->language;

use yii\helpers\Html;
use  yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;

$this->title = ($lang == 'ru') ? 'Отчёты юриста' : 'Юрист хисоботи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-md-12">
        <?php ActiveForm::begin(['action' => Url::to(['/lawyer']), 'method' => 'get']) ?>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="lawyer"><?= ($lang === 'ru') ? 'Юрист' : 'Юрист' ?></label>
                <?php
                echo Select2::widget([
                    'name' => 'Lawyer[user_id]',
                    'value' => '',
                    'data' => \yii\helpers\ArrayHelper::map(User::findAll(['role' => 3, 'status' => 10]), 'id', 'fullname'),
                    'options' => ['placeholder' => 'Выберите юриста', 'id' => 'lawyer']
                ]);
                ?>
            </div>
            <div class="col-md-3 form-group">
                <label for="begin"><?= Yii::$app->params['labels_doc_date_start'][$lang] ?></label>
                <input type="date" name="Lawyer[begin]" required class="form-control" id="begin">
            </div>
            <div class="col-md-3 form-group">
                <label for="end"><?= Yii::$app->params['labels_doc_date_end'][$lang] ?></label>
                <input type="date" name="Lawyer[end]" required class="form-control" id="end">
            </div>
            <div class="col-md-3 form-group mt-4">
                <button class="btn btn-success" type="submit"
                        style="width: 49%;"><?= Yii::$app->params['header_search_button'][$lang] ?></button>
                <a href="<?= Url::to(['/lawyer']) ?>"
                   class="btn btn-secondary" style="width: 49%;"><?= Yii::$app->params['labels_reset_button'][$lang] ?></a>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-striped">
            <thead class="table-dark">
            <th>#</th>
            <th><?=Yii::$app->params['client_type'][$lang][0]?></th>
            <th><?=Yii::$app->params['labels_phone'][$lang]?></th>
            <th>Договор № </th>
            <th><?=Yii::$app->params['labels_company'][$lang]?></th>
            <th><?=Yii::$app->params['user_roles'][$lang][2]?></th>
            <th><?=Yii::$app->params['labels_yurist_goday'][$lang]?></th>
            <th><?=($lang === 'ru')?'Неоплаченная сумма':'Туламаган сумма'?></th>
            <th><?=($lang === 'ru')? 'Оплаченная сумма':'Тулаган сумма'?></th>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
