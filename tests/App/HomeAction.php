<?php

declare(strict_types=1);

namespace Forge\User\Tests\App;

use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;

final class HomeAction
{
    public function run(ViewRenderer $viewRenderer): ResponseInterface
    {
        return $viewRenderer->withViewPath('@user/tests/App/storage/view')->render('home');
    }
}
