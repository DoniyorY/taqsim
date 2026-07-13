<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use frontend\components\ReportWidget;
use yii\helpers\Url;
AppAsset::register($this);
Yii::$app->name = 'Lux Gilam'
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?=Yii::$app->request->baseUrl. '/uploads/logo_fav.png'?>">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
<?php if(!yii::$app->user->isGuest): ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?php echo yii::$app->homeUrl; ?>">
                    <img src="<?=Yii::$app->request->baseUrl . '/uploads/logo.png'?>" style="height: 25px;" alt=""></a>
            </div>

        </nav>
    </header>
<?php endif;?>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid">
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-right"> Powered by <a href="http://nextgen.uz/" target="_blank"> NextGen IT</a></p>

        </div>
    </footer>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
