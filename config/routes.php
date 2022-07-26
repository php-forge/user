<?php

declare(strict_types=1);

use Forge\User\Action\AttemptChangeEmailAction;
use Forge\User\Action\ChangeEmailAction;
use Forge\User\Action\ConfirmAction;
use Forge\User\Action\LoginAction;
use Forge\User\Action\LogoutAction;
use Forge\User\Action\ProfileAction;
use Forge\User\Action\RegisterAction;
use Forge\User\Action\RequestAction;
use Forge\User\Action\ResendAction;
use Forge\User\Action\ResetAction;
use Forge\User\Middleware\Guest;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Config\Config;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

/** @var Config $config */

$params = $config->get('params');

return [
    Group::create($params['user']['router']['prefix'])
        ->routes(
            Route::get('/email/attempt[/{id}/{code}]')
                ->action([AttemptChangeEmailAction::class, 'run'])
                ->name('email/attempt'),
            Route::methods(['GET', 'POST'], '/email/change')
                ->name('email-change')
                ->middleware(Authentication::class)
                ->action([ChangeEmailAction::class, 'run']),
            Route::get('/confirm[/{id}/[{code}]]')
                ->name('confirm')
                ->middleware(Guest::class)
                ->action([ConfirmAction::class, 'run']),
            Route::methods(['GET', 'POST'], '/login')
                ->name('login')
                ->middleware(Guest::class)
                ->action([LoginAction::class, 'run']),
            Route::post('/logout')
                ->action([LogoutAction::class, 'run'])
                ->name('logout'),
            Route::methods(['GET', 'POST'], '/profile')
                ->name('profile')
                ->middleware(Authentication::class)
                ->action([ProfileAction::class, 'run']),
            Route::methods(['GET', 'POST'], '/request')
                ->name('request')
                ->middleware(Guest::class)
                ->action([RequestAction::class, 'run']),
            Route::methods(['GET', 'POST'], '/register')
                ->name('register')
                ->middleware(Guest::class)
                ->action([RegisterAction::class, 'run']),
            Route::methods(['GET', 'POST'], '/resend')
                ->name('resend')
                ->middleware(Guest::class)
                ->action([ResendAction::class, 'run']),
            Route::methods(['GET', 'POST'], '/reset[/{id}/{code}]')
                ->name('reset')
                ->middleware(Guest::class)
                ->action([ResetAction::class, 'run']),
        ),
];
