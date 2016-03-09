<?php

Yii::setAlias('@kodeplus', '@app/kodeplus');

$config = [
    'params' => [
        'moduleAutoloadPaths' => ['@kodeplus/modules'],
    ],
    'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
    ]
];

return $config;
