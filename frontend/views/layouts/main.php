<?php

/** @var \yii\web\View $this */

/** @var string $content */

use cinghie\multilanguage\widgets\MultiLanguageWidget;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use frontend\components\ReportWidget;
use yii\helpers\Url;

AppAsset::register($this);
Yii::$app->name = 'Lux Gilam';
$lang = Yii::$app->language;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?= Yii::$app->request->baseUrl . '/uploads/logo_fav.png' ?>">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap"
              rel="stylesheet">
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?php if (!yii::$app->user->isGuest): ?>
        <?php
        $userMenu = \common\models\UserMenuItem::findOne(['user_id' => Yii::$app->user->id]);
        $array = explode(',', $userMenu->link);
        $user_menu = \common\models\UserMenu::find();
        $clients = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 1])->all();
        $noname = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 5])->all();
        $credits_plan = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 2])->all();
        $lawyer = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 3])->all();
        $reports = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 4])->all();
        $credits = $user_menu->where(['IN', 'id', $array])->andWhere(['category' => 0])->all();
        ?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
                <a class="navbar-brand" href="/">
                    <img src="<?= Yii::$app->request->baseUrl . '/uploads/logo.png' ?>" style="height: 25px;"
                         alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll"
                        aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px;">
                        <?php if (!empty($credits)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?= Yii::$app->params['credits'][$lang] ?>
                                </a>
                                <ul class="dropdown-menu ">
                                    <?php foreach ($credits as $i => $item): ?>
                                        <li>
                                            <a class="dropdown-item"
                                               href="<?= $item->link ?>"><?= $item->{"content_$lang"} ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($clients)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?= Yii::$app->params['labels_clients'][$lang] ?> </a>
                                <ul class="dropdown-menu ">
                                    <?php foreach ($clients as $item): ?>
                                        <li><a class="dropdown-item"
                                               href="<?= $item->link ?>"><?= $item->{"content_$lang"} ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                        <?php endif; ?>
                        <?php if (!empty($credits_plan)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?= Yii::$app->params['credits_plan'][$lang] ?> </a>
                                <ul class="dropdown-menu ">
                                    <?php foreach ($credits_plan as $item): ?>
                                        <li><a class="dropdown-item"
                                               href="<?= $item->link ?>"><?= $item->{"content_$lang"} ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($lawyer)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?= Yii::$app->params['lawyer_data'][$lang] ?>
                                </a>
                                <ul class="dropdown-menu ">
                                    <?php foreach ($lawyer as $item): ?>
                                        <li><a class="dropdown-item"
                                               href="<?= $item->link ?>"><?= $item->{"content_$lang"} ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($reports)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?= Yii::$app->params['reports'][$lang] ?> </a>
                                <ul class="dropdown-menu ">
                                    <?php foreach ($reports as $item): ?>
                                        <li><a class="dropdown-item"
                                               href="<?= $item->link ?>"><?= $item->{"content_$lang"} ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($noname)): ?>
                            <?php foreach ($noname as $item): ?>
                                <li class="nav-item">
                                    <a class="nav-link " href="<?= $item->link ?>">
                                        <?= $item->{"content_$lang"} ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link btn btn-warning btn-sm text-dark" data-toggle="collapse" href="#search"
                               role="button" aria-expanded="false" aria-controls="search">
                                <i class="fa fa-search"
                                   aria-hidden="true"></i> <?= Yii::$app->params['header_search_button'][$lang] ?>  </a>
                        </li>

                        <li class="nav-item ml-3">
                            <a class="nav-link btn btn-info btn-sm text-dark" data-toggle="collapse" href="#kassa"
                               role="button" aria-expanded="false" aria-controls="kassa" onclick="ajaxRequest()">
                                <i class="fa fa-money"
                                   aria-hidden="true"></i> <?= Yii::$app->params['show_kassa'][$lang] ?> </a>
                        </li>
                        <script>
                            function refresh() {
                                window.location.reload("Refresh")
                            }
                        </script>
                        <li class="nav-item">
                            <button type="button" class="nav-link btn btn-primary btn-sm text-white ml-3"
                                    onclick="refresh(this)">
                                <i class="fa fa-refresh"
                                   aria-hidden="true"></i> <?= Yii::$app->params['refresh_btn'][$lang] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <?= MultiLanguageWidget::widget([
                                'addCurrentLang' => true, // add current lang
                                'calling_controller' => $this->context,
                                'image_type' => 'classic', // classic or rounded
                                'link_home' => false, // true or false
                                'widget_type' => 'classic', // classic or selector
                                'width' => '28'
                            ]); ?>
                        </li>

                    </ul>
                    <ul class="navbar-nav d-flex my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px;">

                        <li class="nav-item dropdown dropleft ">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                               aria-expanded="false">
                                <?= Yii::$app->params['user_roles'][$lang][Yii::$app->user->identity->role] ?>:
                                <span class="badge badge-primary"><?= Yii::$app->user->identity->username ?></span>
                            </a>
                            <ul class="dropdown-menu mt-5">
                                <!-- <li><a class="dropdown-item" href="#">---</a></li> -->

                                <li>
                                    <form class="form-inline" action="<?= Url::to(['/site/logout']) ?>" method="post">
                                        <input type="hidden" name="_csrf-frontend"
                                               value="QgSyQqjSA3gysDDP46r8A8HrifgncQpbxclwCHsz0bQMStF67JpZFEj2BY2x5q5CoNm7iEUfPhenjQViNkCk1Q==">
                                        <button type="submit"
                                                class="dropdown-item btn-link logout"><?= Yii::$app->params['logout'][$lang] ?></button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>


        <div class="container-fluid" style="padding-top: 60px;">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4 mb-2">
                    <div class="collapse" id="search" style="margin-top:15px;">
                        <form class="d-flex mx-5" action="<?= Url::to(['/credit/search']) ?>" method="get">
                            <input class="form-control mr-2" type="search"
                                   placeholder="<?= Yii::$app->params['header_search'][$lang] ?>"
                                   aria-label="Search" name="id">
                            <button class="btn btn-outline-success" type="submit">
                                <?= Yii::$app->params['header_search_button'][$lang] ?></button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="collapse" id="kassa">
                <table class="table table-bordered table-sm text-center">
                    <?= $this->render('_report_table', ['lang' => $lang]) ?>
                    <?php /*= ReportWidget::widget(); */ ?>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
                </div>

                <div class="col-sm-4">
                    <p class="float-right"> Powered by <a href="http://nextgen.uz/" target="_blank"> NextGen IT</a></p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
