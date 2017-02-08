<?php

Yii::setAlias('@kodeplus', '@app/kodeplus');

$config = [
    'params' => [
        'moduleAutoloadPaths' => ['@kodeplus/modules'],
    ],
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'humhub\modules\user\authclient\Facebook',
                    'clientId' => getenv('FACEBOOK_CLIENT_ID'),
                    'clientSecret' => getenv('FACEBOOK_CLIENT_SECRET'),
                ],
                'google' => [
                    'class' => 'humhub\modules\user\authclient\Google',
                    'clientId' => getenv('GOOGLE_CLIENT_ID'),
                    'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
                ],
                'baseAuth' => [
                    'class' => 'humhub\modules\user\authclient\Password'
                ]

            ],
        ],
        'bitly' => [
            'class' => 'kodeplus\\modules\\announcement\\components\\VGBitly',
            'login' => getenv('BITLY_LOGIN'),
            'apiKey' => getenv('BITLY_API_KEY'),
            'format' => getenv('BITLY_FORMAT'),
        ],
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('REDIS_HOST'),
            'port' => 6379,
            'database' => 0
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'servers' => [
                [
                    'host' => getenv('MEMCACHED_HOST'),
                    'port' => 11211,
                    'weight' => 100,
                ],
            ],
            'useMemcached' => true
        ]
    ],
    'bootstrap' => [
        'kodeplus\components\bootstrap\KodeplusCoreLoader',
        'kodeplus\components\bootstrap\PrimaryColorLoader',
        'kodeplus\components\bootstrap\SpaceEventLoader'
    ],
];

return $config;
