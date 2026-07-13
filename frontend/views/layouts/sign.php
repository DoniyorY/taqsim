<?php

/** @var \yii\web\View $this */
/** @var string $content */

use cinghie\multilanguage\widgets\MultiLanguageWidget;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
Yii::$app->name = 'Lux Gilam'
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => \yii\helpers\Url::to(['/sign']),
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
        </div>
        <div class="container-fluid">
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <?= MultiLanguageWidget::widget([
                'addCurrentLang' => true, // add current lang
                'calling_controller' => $this->context,
                'image_type'  => 'classic', // classic or rounded
                'link_home'   => false, // true or false
                'widget_type' => 'classic', // classic or selector
                'width'       => '28'
            ]); ?>
            <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-right">Powered by <a href="https://diamondsolutions.uz" target="_blank">Diamond Solutions</a></p>
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
