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

    ],
    'bootstrap' => [
        'kodeplus\modules\kodeplus_space\components\EventBootstrap',
        'kodeplus\modules\kodeplus_user\components\EventBootstrap'

    ]
];

return $config;
