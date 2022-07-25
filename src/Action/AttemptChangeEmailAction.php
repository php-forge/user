<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\ActiveRecord\Account;
use Forge\User\Repository\AccountRepository;
use Forge\User\Service\AttemptEmailChangeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\User\CurrentUser;

final class AttemptChangeEmailAction extends Action
{
    /**
     * @throws Exception|InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(
        AccountRepository $accountRepository,
        AttemptEmailChangeService $attemptEmailChangeService,
        CurrentRoute $currentRoute,
        CurrentUser $currentUser,
        RequestHandlerInterface $requestHandler,
        ServerRequestInterface $serverRequest
    ): ResponseInterface {
        $id = $currentRoute->getArgument('id');
        $code = $currentRoute->getArgument('code');

        if ($id === null || $code === null || ($account = $accountRepository->findById($id)) === null) {
            return $requestHandler->handle($serverRequest);
        }

        /** @var Account $account */
        if ($attemptEmailChangeService->run($code, $account) === false) {
            return $requestHandler->handle($serverRequest);
        }

        return match ($currentUser->isGuest()) {
            true => $this->redirect('home'),
            default => $this->redirect('email-change'),
        };
    }
}
