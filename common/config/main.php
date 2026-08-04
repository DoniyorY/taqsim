<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
   'modules' => [
      'audit' => [
         'class' => \bedezign\yii2\audit\Audit::class,
         'db' => 'db',
         
         'trackActions' => ['*'],
         
         'ignoreActions' => [
            'audit/*',
            'debug/*',
            'gii/*',
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
        'playmobile' => [
            'class' => \rakhmatov\playmobile\components\Connection::class,
            'username' => 'taqsim_savdo',
            'password' => 'Msl2&aj$8217',
        ],
       'formatter' => [
          'decimalSeparator' => ',',
          'thousandSeparator' => ' ',
       ],
    ],

];
