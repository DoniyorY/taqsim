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
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Дата начала</label>
                            <input type="date" class="form-control" id="exampleInputEmail1"
                                   aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Дата окончания</label>
                            <input type="date" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block mt-4">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
        <div class="col-md-6">
            <h3>Отчёт по кассе (Сегодня)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <h3>Отчёт по оплаченным кредитам (Сегодня)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <h3>Отчёт по не оплаченным кредитам (Сегодня)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Отчёт по кассе (За всё время)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <h3>Отчёт по оплаченным кредитам (За всё время)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <h3>Отчёт по не оплаченным кредитам (За всё время)</h3>
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
                    <td>№1 от 31.12.2000</td>
                    <td>2 000 000</td>
                    <td>1 000 000</td>
                    <td>1 000 000</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
