<?php

Yii::$app->name = 'Lux Gilam';
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
    ],
]);
$menuItems = [


    ['label' => 'Отчеты', 'items' => [
        ['label' => '', 'url' => ['/report/index']],
        ['label' => '', 'url' => ['/report/credit']],
    ]],

];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => '', 'url' => ['/site/login']];
} else {

}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ml-auto'],
    'items' => $menuItems,
]);
NavBar::end();
?>