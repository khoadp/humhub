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
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => getenv('FACEBOOK_CLIENT_ID'),
                    'clientSecret' => getenv('FACEBOOK_CLIENT_SECRET'),
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
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
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => getenv('ELASTICSEARCH_SERVER_ENDPOINT') ? getenv('ELASTICSEARCH_SERVER_ENDPOINT') : '127.0.0.1:9200'],
            ],
        ],

    ],
    'bootstrap' => [
        'kodeplus\modules\kodeplus_space\components\EventBootstrap',
        'kodeplus\modules\kodeplus_user\components\EventBootstrap'

    ]
];

return $config;
