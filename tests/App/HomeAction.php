<?php

declare(strict_types=1);

namespace Forge\User\Tests\App;

use Forge\Service\View;
use Psr\Http\Message\ResponseInterface;

final class HomeAction
{
    public function run(View $view): ResponseInterface
    {
        return $view->withViewPath('@user/tests/App/storage/view')->render('home');
    }
}
