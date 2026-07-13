<?php
$lang = Yii::$app->language;

$this->title = Yii::$app->params['reports'][$lang];

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2><?= \yii\helpers\Html::encode($this->title) ?></h2>
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Дата начала</label>
                            <input type="date" class="form-control" id="exampleInputEmail1"
                                   aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Дата окончания</label>
                            <input type="date" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Магазин</label>
                            <input type="text" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block mt-4">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
        <div class="col-md-6">
            <h3> (Сегодня)</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по кассe</td>
                    <td><?=Yii::$app->formatter->asDecimal($today_card+$today_cash, 0)?></td>
                    <td><?=Yii::$app->formatter->asDecimal($today_cash, 0)?></td>
                    <td><?=Yii::$app->formatter->asDecimal($today_card, 0)?></td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по оплаченным кредитам</td>
                    <td><?=Yii::$app->formatter->asDecimal($today_plan_total, 0)?></td>
                    <td>1 </td>
                    <td>1 </td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по не оплаченным кредитам</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3> (За всё время)</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по кассе</td>
                    <td><?=Yii::$app->formatter->asDecimal($all_time_cash+$all_time_card, 0)?></td>
                    <td><?=Yii::$app->formatter->asDecimal($all_time_cash, 0)?></td>
                    <td><?=Yii::$app->formatter->asDecimal($all_time_card, 0)?></td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по оплаченным кредитам</td>
                    <td><?=Yii::$app->formatter->asDecimal($plan_total, 0)?></td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Касса</th>
                    <th>Общая сумма</th>
                    <th>Наличные</th>
                    <th>Пластик</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Отчёт по не оплаченным кредитам</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
