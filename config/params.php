<?php

declare(strict_types=1);

use Yiisoft\Definitions\Reference;

return [
    'forge' => [
        'mailer' => [
            'from' => 'administrator@example.com',
            'signatureImage' => '@images/mail-yii3-signature.png',
            'signatureText' => 'Powered by Yii Framework',
            'translatorCategory' => 'user',
            'viewPath' => '@user/storage/mailer',
        ],
    ],

    'user' => [
        'router' => [
            'prefix' => null,
        ],
    ],

    'yiisoft/aliases' => [
        'aliases' => [
            '@user' => dirname(__DIR__),
            '@avatars' => '@assets/images/avatar',
            '@bootstrap5' => '@user/config/widget/bootstrap5',
        ],
    ],

    'yiisoft/cookies' => [
        'secretKey' => '53136271c432a1af377c3806c3112ddf',
    ],

    'yiisoft/translator' => [
        'categorySources' => [
            Reference::to('translation.user'),
        ],
    ],

    'yiisoft/yii-db-migration' => [
        'updateNamespaces' => [
            'Forge\\User\\Migration',
        ],
    ],
];
