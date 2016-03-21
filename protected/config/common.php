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
            ],
        ],
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
    ]
];

return $config;
