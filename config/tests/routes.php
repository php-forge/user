<?php

declare(strict_types=1);

use Forge\User\Tests\App\HomeAction;
use Yiisoft\Router\Route;

return [
    Route::get('/')->action([HomeAction::class, 'run'])->name('home'),
];
