<?php

declare(strict_types=1);

use Forge\User\Tests\App\Middleware\Locale;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Cookies\CookieMiddleware;
use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Definitions\Reference;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Session\SessionMiddleware;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Login\Cookie\CookieLoginMiddleware;

return [
    'locales' => ['en' => 'en-US', 'es' => 'es-ES', 'ru' => 'ru-RU'],

    'middlewares' => [
        ErrorCatcher::class,
        SessionMiddleware::class,
        CookieMiddleware::class,
        CookieLoginMiddleware::class,
        Locale::class,
        Router::class,
    ],

    'yiisoft/aliases' => [
        'aliases' => [
            '@root' => dirname(__DIR__, 2),
            '@app' => '@root/tests/App',
            '@assets' => '@app/public/assets',
            '@assetsUrl' => '/assets',
            '@images' => '@app/public/image',
            '@layout' => '@app/storage/layout',
            '@npm' => '@root/node_modules',
            '@resources' => '@runtime',
            '@runtime' => '@root/tests/_output/runtime',
            '@storage' => '@root/storage',
            '@vendor' => '@root/vendor',
            '@views' => '@storage/view',
        ],
    ],

    'yiisoft/translator' => [
        'categorySources' => [
            Reference::to('translation.app'),
        ],
    ],

    'yiisoft/view' => [
        'basePath' => '@views',
        'parameters' => [
            'aliases' => Reference::to(Aliases::class),
            'csrfToken' => Reference::to(CsrfTokenInterface::class),
            'currentRoute' => Reference::to(CurrentRoute::class),
            'currentUser' => Reference::to(CurrentUser::class),
            'flash' => Reference::to(Flash::class),
            'translator' => Reference::to(TranslatorInterface::class),
            'urlGenerator' => Reference::to(UrlGeneratorInterface::class),
        ],
        'theme' => [
            'pathMap' => [
                '@layout' => '@layout',
            ],
        ],
    ],

    'yiisoft/yii-db-migration' => [
        'updateNamespaces' => [
            'Forge\\User\\Tests\\App\\Migration',
        ],
    ],
];
