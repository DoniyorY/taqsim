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
            'sign/search',
            'sign/view',
            
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
       'requestFirewall' => [
          'class' => common\components\security\RequestFirewall::class,
          
          // Первое время только журнал.
          'blockRequests' => false,
          
          // Потом можно включить.
          'blockScore' => 10,
          
          'excludedPaths' => [
             '/debug/',
             '/gii/',
             '/site/upload',
          ],
       ],
    ],

];
