<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error'],
                    'categories' => ['cron.checkPlans'],
                    'logFile' => '/var/log/yii_check_plans.log',
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error'],
                    'categories' => ['cron.sendWarningSms'],
                    'logFile' => '/var/log/yii_send_warning_sms.log',
                    'logVars' => [],
                ],
            ],
        ],
    ],
    'params' => $params,
];
