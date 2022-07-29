<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Token;
use Forge\User\Form\ResetForm;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Repository\TokenRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Http\Method;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Session\Flash\Flash;

final class ResetAction extends Action
{
    /**
     * @throws StaleObjectException|Throwable
     */
    public function run(
        AccountRepository $accountRepository,
        CurrentRoute $currentRoute,
        Flash $flash,
        ModuleInterface $module,
        RequestHandlerInterface $requestHandler,
        ServerRequestInterface $serverRequest,
        TokenRepository $tokenRepository
    ): ResponseInterface {
        /** @psalm-var array<string, array|string> $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        $id = $currentRoute->getArgument('id');
        $code = $currentRoute->getArgument('code');

        // Create the form
        $resetForm = new ResetForm($this->translator);

        if ($id === null || ($account = $accountRepository->findById($id)) === null || $code === null) {
            return $requestHandler->handle($serverRequest);
        }

        /**
         * @var Token|null $token
         * @var Account $account
         */
        $token = $tokenRepository->findByOneCondition(
            ['identity_id' => $account->getIdentityId(), 'code' => $code, 'type' => Token::TYPE_RECOVERY]
        );

        if ($token === null || $token->isExpired(0, $module->getTokenRecover())) {
            return $requestHandler->handle($serverRequest);
        }

        if (
            $method === Method::POST
            && $resetForm->load($body)
            && $this->validate($resetForm)
            && !$token->isExpired(0, $module->getTokenRecover())
        ) {
            $token->delete();
            $account->passwordHashUpdate($resetForm->getPassword());
            $flash->add('forge.user', ['content' => $this->translator->translate('reset.recovery.success'), 'type' => 'success']);

            return $this->redirect('login');
        }

        return $this->viewRenderer
            ->withViewPath('@user/storage/view')
            ->render(
                'reset',
                [
                    'body' => $body,
                    'code' => $code,
                    'formModel' => $resetForm,
                    'id' => $id,
                    'translator' => $this->translator,
                ],
            );
    }
}
