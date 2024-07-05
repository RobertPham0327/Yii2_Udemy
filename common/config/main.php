<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'formatter' => [
            'dateFormat' =>  'yyyy-MM-dd'               // Formtter for displaying date form
        ],
        // ROLE BASED ACCESS CONTROL
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
];
