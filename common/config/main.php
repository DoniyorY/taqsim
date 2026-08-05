<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
   'modules' => [
      'audit' => [
         'class' => \bedezign\yii2\audit\Audit::class,
         'db' => 'auditDb',
         
         'trackActions' => ['*'],
         
         'ignoreActions' => [
            'audit/*',
            'debug/*',
            'gii/*',
            'site/error',
            'site/index',
         ],
         
         // Поставь свою реальную RBAC-роль администратора
         'accessRoles' => [0],
      ],
   ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
       'formatter' => [
          'decimalSeparator' => ',',
          'thousandSeparator' => ' ',
       ],
    ],

];
