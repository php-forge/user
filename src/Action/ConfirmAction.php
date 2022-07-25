<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Token;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Repository\TokenRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\User\CurrentUser;

final class ConfirmAction extends Action
{
    /**
     * @throws Exception|NotSupportedException|StaleObjectException|Throwable
     */
    public function run(
        AccountRepository $accountRepository,
        CurrentRoute $currentRoute,
        CurrentUser $currentUser,
        Flash $flash,
        ModuleInterface $module,
        RequestHandlerInterface $requestHandler,
        ServerRequestInterface $serverRequest,
        TokenRepository $tokenRepository
    ): ResponseInterface {
        $id = $currentRoute->getArgument('id');
        $code = $currentRoute->getArgument('code');

        /** @var string $ip */
        $ip = $serverRequest->getServerParams()['REMOTE_ADDR'];

        if ($id === null || ($account = $accountRepository->findById($id)) === null || $code === null) {
            return $requestHandler->handle($serverRequest);
        }

        /**
         * @var Token|null $token
         * @var Account $account
         */
        $token = $tokenRepository->findByOneCondition(
            ['identity_id' => $account->getIdentityId(), 'code' => $code, 'type' => Token::TYPE_CONFIRMATION]
        );

        if ($token === null || $token->isExpired($module->getTokenConfirm())) {
            return $requestHandler->handle($serverRequest);
        }

        $identity = $account->identity;

        if (!$token->isExpired($module->getTokenConfirm()) && $identity !== null) {
            $token->delete();

            $account->updateAttributes([
                'confirmed_at' => time(),
                'ip_last_login' => $ip,
                'last_login_at' => time(),
                'unconfirmed_email' => '',
            ]);

            $currentUser->login($identity);

            $flash->add(
                'forge.user',
                ['content' => $this->translator()->translate('confirm.email.success'), 'type' => 'success'],
            );
        }

        return $this->redirect('home');
    }
}
