<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
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
    ],

];
