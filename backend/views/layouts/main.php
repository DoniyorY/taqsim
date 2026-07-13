<?php

/** @var \yii\web\View $this */

/** @var string $content */

use backend\assets\AppAsset;
use cinghie\multilanguage\widgets\MultiLanguageWidget;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
if (Yii::$app->user->isGuest) {
    header('Location: ' . \yii\helpers\Url::home());
    return;
}
AppAsset::register($this);
Yii::$app->name = 'L.G Admin Panel';
$lang = Yii::$app->language;
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
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);
        $menuItems = [
            ['label'=>'RU','url'=>'https://taqsimsavdo.uz/admin/ru'],
            ['label'=>'UZ','url'=>'https://taqsimsavdo.uz/admin/uz'],
            ['label' => Yii::$app->params['settings'][$lang], 'items' => [
                ['label' => Yii::$app->params['labels_company'][$lang], 'url' => ['/company/index']],
                ['label' => Yii::$app->params['labels_region'][$lang], 'url' =>['/region/index']],
                ['label' => Yii::$app->params['labels_credit_type'][$lang], 'url' => ['/credit-type/index']],
                ['label' => Yii::$app->params['labels_user'][$lang], 'url' => ['/user/index']],
                ['label' => Yii::$app->params['labels_settings'][$lang], 'url' => ['/settings/index']],
                ['label' => Yii::$app->params['labels_settings_db'][$lang], 'url' => ['/db/index']],
            ]],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => Yii::$app->params['login'][$lang], 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    Yii::$app->params['logout'][$lang] .' (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">

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
            <p class="float-right">Powered by <a href="http://diamondsolutions.uz" target="_blank"> Diamond Solutions</a></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
