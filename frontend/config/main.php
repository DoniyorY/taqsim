<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru',
    'timeZone' => 'Asia/Tashkent',
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ],
        'api' => [
            'class' => 'frontend\modules\api\Atmos',
        ],
        /*'analytics' => [
            'class' => 'frontend\modules\analytics\Module'
        ],*/
    ],

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['ru', 'uz'], // List all supported languages here
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'invoice/contract/<id>' => 'credit-invoice/contract',
                'credits' => 'credit/index',
                'credit/detail/<id>' => 'credit/view',
                'POST api/getContractDebt' => 'api/algenix/get-contract-debt',
                'POST api/sendPaymentToOutsideSource' => 'api/algenix/get-payment-response',
                'GET api/algenix/get-debtors-from-outside-source-by-branch-id' => 'api/algenix/get-debtors',
            ]
        ],
        'formatter' => [
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
        ],

    ],
    'params' => $params,
];
