<?php

use yii\helpers\Url;

$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
$lang = Yii::$app->language;
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<div class="row p-2" style="background: #f3f3f9">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= $params['company_count'][$lang] ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= $company_count ?>
                                </h4>
                                <a href="#" class="text-decoration-underline text-white">
                                    1
                                </a>
                            </div>
                            <div class="border rounded p-1 bg-info text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-buildings text-white"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= $params['credit_count'][$lang] ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= Yii::$app->formatter->asDecimal($credit_count, 0) ?>
                                </h4>
                                <a href="<?= Url::to(['/credit/index']) ?>"
                                   class="text-decoration-underline"><?= $params['show_all_credits'][$lang] ?></a>
                            </div>
                            <div class="border rounded p-1 bg-success text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-file-earmark-text text-white"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <?= $params['unpayed_credit_count'][$lang] ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= $unpayed_plans ?>
                                </h4>
                                <a href="<?= Url::to(['/credit-plan/late']) ?>" class="text-decoration-underline"
                                   target="_blank">
                                    <?= $params['show_all_unpayed'][$lang] ?>
                                </a>
                            </div>
                            <div class="border rounded p-1 bg-success text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-file-earmark-check text-white"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <?= $params['client_count'][$lang] ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= Yii::$app->formatter->asDecimal($client_count, 0) ?>
                                </h4>
                                <a href="<?= Url::to(['/client/index']) ?>" class="text-decoration-underline">
                                    <?= $params['show_all_clients'][$lang] ?>
                                </a>
                            </div>
                            <div class="border rounded p-1 bg-primary text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-person-lines-fill text-white"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <?= $params['today_plans'][$lang] ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= Yii::$app->formatter->asDecimal($plan_today_sum, 0) ?> UZS
                                </h4>
                                <a href="<?= Url::to(['/credit-plan/today']) ?>" class="text-decoration-underline">
                                    <?= $params['show_today_plans'][$lang] ?>
                                </a>
                            </div>
                            <div class="border rounded p-1 bg-warning text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-cash-stack text-dark"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <?= $params['show_kassa'][$lang] ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-2">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                    <?= Yii::$app->formatter->asDecimal($payment_today_sum, 0) ?> UZS
                                </h4>
                                <a href="<?= Url::to(['/payments/today']) ?>" class="text-decoration-underline">
                                    Касса
                                </a>
                            </div>
                            <div class="border rounded p-1 bg-secondary text-center" style="width: 35px;">
                        <span class=" rounded fs-3">
                            <i class="bi bi-coin text-white"></i>
                        </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
        </div>
        <hr class="mt-3">
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Фильтр</h4>
            </div>
            <div class="card-body">
                <?= $this->render('_search', [
                    'before' => $before,
                    'now' => $now
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-8 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['created_credit_count'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="companyColumnChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['payment_methods'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="companyPieChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-7 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['payment_graphic'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="paymentLineChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['company_income'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="paymentCompanyBarChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['avg_age'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="clientAgeBarChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-7 mt-2">
        <div class="card">
            <div class="card-header">
                <h4><?= $params['avg_age_delay'][$lang] ?></h4>
            </div>
            <div class="card-body">
                <div id="clientDelayAgeBarChart" data-begin="<?= $before ?>" data-end="<?= $now ?>"></div>
            </div>
        </div>
    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous">
</script>
<?php

$this->registerJsFile('https://cdn.jsdelivr.net/npm/apexcharts');
$this->registerJsFile("$base/js/analytics/index.apexcharts.js")
?>
