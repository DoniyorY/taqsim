<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'timeZone'=>'Tashkent/Asia',
    'name'=>'CREDIT ADMIN',
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ],
       'audit' => [
          'class' => bedezign\yii2\audit\Audit::class,
          'ignoreActions' => ['audit/*', 'debug/*'],
          // Maximum age (in days) of the audit entries before they are truncated
          'maxAge' => 'debug',
          // IP address or list of IP addresses with access to the viewer, null for everyone (if the IP matches)
          'accessIps' => ['127.0.0.1', '192.168.*'],
          // Role or list of roles with access to the viewer, null for everyone (if the user matches)
          'accessRoles' => [0],
          // User ID or list of user IDs with access to the viewer, null for everyone (if the role matches)
          'accessUsers' => [10],
          // Compress extra data generated or just keep in text? For people who don't like binary data in the DB
          'compressData' => true,
          // The callback to use to convert a user id into an identifier (username, email, ...). Can also be html.
          'userIdentifierCallback' => ['app\models\User', 'userIdentifierCallback'],
          // If the value is a simple string, it is the identifier of an internal to activate (with default settings)
          // If the entry is a '<key>' => '<string>|<array>' it is a new panel. It can optionally override a core panel or add a new one.
          'panels' => [
             'audit/request',
             'audit/error',
             'audit/trail',
          ],
       ],
    ],
    'homeUrl'=>'/admin',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl'=>'/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'languages' => ['ru','uz'], // List all supported languages here
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=> [
                
            ]
        ],

    ],
    'params' => $params,
];
