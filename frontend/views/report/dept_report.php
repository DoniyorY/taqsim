<?php

use yii\helpers\Html;

/** @var string $title */

$this->title = $title;
$lang = Yii::$app->language;
$this->params['breadcrumbs'][] = $this->title;
$get = Yii::$app->request->get('Search');

?>
<div class="row">
    <div class="col-md-6">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="col-md-6 text-right">
        <form action="<?= \yii\helpers\Url::to(['dept']) ?>" method="post">
            <?= Html::input('text', Yii::$app->request->csrfParam, Yii::$app->request->csrfToken, ['type' => 'hidden']) ?>
            <?php
            if (!empty($get)) {
                echo Html::input('text', 'Search[start]', $get['start'], ['type' => 'hidden']);
                echo Html::input('text', 'Search[end]', $get['end'], ['type' => 'hidden']);
                echo Html::input('number', 'Search[id]', $get['id'] ?? '', ['type' => 'hidden']);
            }
            ?>
            <?= Html::submitButton(Yii::$app->params['export_to'][$lang], ['class' => 'btn btn-primary']) ?>
        </form>
    </div>
    <div class="col-md-12">
        <?php \yii\widgets\ActiveForm::begin(['method' => 'get', 'action' => \yii\helpers\Url::to(['dept'])]) ?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <?= Html::label('Номер договора', 'credit_id') ?>
                    <?= Html::input('text', 'Search[id]', $get['id'] ?? '', ['class' => 'form-control', 'id' => 'credit_id']) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Дата начала') ?>
                    <?= Html::input('date', 'Search[start]', Yii::$app->formatter->asDate($date_begin, 'php:Y-m-d'), ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Дата конца') ?>
                    <?= Html::input('date', 'Search[end]', Yii::$app->formatter->asDate($date_end, 'php:Y-m-d'), ['class' => 'form-control']) ?>
                </div>
            </div>

            <div class="col-md-1">
                <?= Html::submitButton('Поиск', ['class' => 'btn mt-4 btn-primary w-100']) ?>
            </div>
            <div class="col-md-1">
                <?= Html::a('Сбросить', ['dept'], ['class' => 'btn mt-4 btn-warning w-100']) ?>
            </div>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Форма для экспорта отмеченных строк -->
        <form id="export-selected-form" action="<?= \yii\helpers\Url::to(['dept']) ?>" method="post">
            <?= Html::input('text', Yii::$app->request->csrfParam, Yii::$app->request->csrfToken, ['type' => 'hidden']) ?>
            <?php
            if (!empty($get)) {
                // Передаем диапазон дат, если был выбран в фильтре
                echo Html::input('text', 'Search[start]', $get['start'], ['type' => 'hidden']);
                echo Html::input('text', 'Search[end]', $get['end'], ['type' => 'hidden']);
            }
            ?>
            <div class="d-flex justify-content-between">
                <!-- Кнопка для экспорта отмеченных строк -->
                <?= Html::submitButton('Экспортировать отмеченные', ['class' => 'btn btn-success my-2']) ?>

                <?php
                $params = ['dept', 'algenix' => 1];

                if ($search = Yii::$app->request->get('Search')) {
                    $params['Search'] = [
                        'start' => $search['start'] ?? '',
                        'end' => $search['end'] ?? '',
                        'id' => $search['id'] ?? ''
                    ];
                }

                echo Html::a(
                    'Показать отмеченные Algenix',
                    $params,
                    ['class' => 'btn btn-info my-2']
                );
                ?>

            </div>

            <table class="table table-sm table-bordered text-center">
                <thead>
                <tr>
                    <th><input type="checkbox" style="width: 15px;height: 15px;" id="select-all"></th>
                    <th>#</th>
                    <th>ISM</th>
                    <th>FAMILIYA</th>
                    <th>OTASINING ISMI</th>
                    <th>JSHSHR</th>
                    <th>TUG’ILGAN SANA</th>
                    <th>PASSPORT</th>
                    <th>PASSPORT BERILGAN SANA</th>
                    <th>FILIAL ID</th>
                    <th>TO’LOV KUNI</th>
                    <th>OYLIK TO’LOVI</th>
                    <th>JORIY OYGA QARZ</th>
                    <th>JAMI QARZ</th>
                    <th>EXT_ID</th>
                    <th style="width: 10%">IZOH</th>
                    <th>ALGENIX</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $month_dept = 0;
                $total_dept = 0;
                foreach ($credits as $item):
                    if ($item['total_ost'] < 100) continue; ?>
                    <tr>
                        <!-- Чекбокс для отметки строки -->
                        <td>
                            <input type="checkbox" class="row-check" name="CreditId[]"
                                   style="width: 15px; height: 15px " value="<?= $item['credit_id'] ?>">
                        </td>
                        <td><?= $i++; ?></td>
                        <td><?= $item['first_name'] ?></td>
                        <td><?= $item['last_name'] ?></td>
                        <td><?= $item['middle_name'] ?></td>
                        <td><?= $item['passport_pinfl'] ?></td>
                        <td><?= $item['birthday'] ?></td>
                        <td><?= $item['passport_numb'] ?></td>
                        <td><?= $item['passport_begindate'] ?></td>
                        <td><?= $item['company_id'] ?></td>
                        <td><?= Yii::$app->formatter->asDate($item['pay_day'], 'php:j') ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($item['pay_summa'], 0) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($item['month_ost'], 0);
                            $month_dept += $item['month_ost']; ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($item['total_ost'], 0);
                            $total_dept += $item['total_ost'] ?></td>
                        <td><?= $item['credit_id'] ?></td>
                        <td><?= $item['content'] ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input
                                        class="form-check-input autopay-toggle"
                                        type="checkbox"
                                        role="switch"
                                        style="width: 20px; height: 20px;"
                                        data-id="<?= $item['credit_id'] ?>"
                                        id="al_checkbox_<?= $i ?>"
                                    <?= $item['algenix_autopay'] == 1 ? 'checked' : '' ?>>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
                    <td></td>
                    <td></td>
                    <th>Итого: </th>
                    <td><?= Yii::$app->formatter->asDecimal($month_dept, 0) ?></td>
                    <td><?= Yii::$app->formatter->asDecimal($total_dept, 0) ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>

        </form>
    </div>
</div>

<!-- Скрипт для функционала "выделить все" -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
<script>

    $(function () {
        // По клику на главный чекбокс отмечаем/снимаем все строки
        $('#select-all').on('change', function () {
            $('.row-check').prop('checked', $(this).prop('checked'));
        });
        // Если снять галочку у любой строки, убрать выделение "select-all"; если отметить все вручную – установить "select-all"
        $('.row-check').on('change', function () {
            if (!$(this).prop('checked')) {
                $('#select-all').prop('checked', false);
            } else if ($('.row-check:checked').length === $('.row-check').length) {
                $('#select-all').prop('checked', true);
            }
        });
    });

    $(document).on('change', '.autopay-toggle', function () {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const status = checkbox.is(':checked') ? 1 : 0;
        console.log(id)
        console.log(status)

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['update-autopay'])?>', // URL экшена
            type: 'POST',
            data: {id: id, status: status},
            success: function (res) {
                if (res.success) {
                    console.log('Статус успешно обновлён');
                } else {
                    alert('Ошибка при обновлении статуса!');
                    checkbox.prop('checked', !status); // откат
                }
            },
            error: function () {
                alert('Ошибка соединения с сервером!');
                checkbox.prop('checked', !status); // откат
            }
        });
    });
</script>
