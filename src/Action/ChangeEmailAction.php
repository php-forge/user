<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Identity;
use Forge\User\Form\EmailChangeForm;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Service\DefaultEmailChangeService;
use Forge\User\Service\InsecureEmailChangeService;
use Forge\User\Service\SecureEmailChangeService;
use OutOfBoundsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Http\Method;
use Yiisoft\User\CurrentUser;

final class ChangeEmailAction extends Action
{
    /**
     * @throws Exception|InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(
        AccountRepository $accountRepository,
        CurrentUser $currentUser,
        DefaultEmailChangeService $defaultEmailChangeService,
        InsecureEmailChangeService $insecureEmailChangeService,
        ModuleInterface $module,
        RequestHandlerInterface $requestHandler,
        SecureEmailChangeService $secureEmailChangeService,
        ServerRequestInterface $serverRequest
    ): ResponseInterface {
        /** @psalm-var array<string, array|string> $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();
        $identity = $currentUser->getIdentity();
        $account = $identity instanceof Identity ? $identity->account : null;

        if (!$account instanceof Account) {
            return $requestHandler->handle($serverRequest);
        }

        $emailChangeForm = new EmailChangeForm($account, $accountRepository, $this->translator);

        if ($method === Method::POST && $emailChangeForm->load($body) && $this->validate($emailChangeForm)) {
            $mailer = $module->mailer();
            $email = $emailChangeForm->getEmail();

            if ($email === $account->getEmail() && empty($account->getUnconfirmedEmail())) {
                $account->unconfirmedEmail('');
            } elseif ($email !== $account->getEmail()) {
                match ($mailer->getEmailStrategy()) {
                    $mailer::STRATEGY_INSECURE => $insecureEmailChangeService->run($email, $account),
                    $mailer::STRATEGY_DEFAULT => $defaultEmailChangeService->run($email, $account),
                    $mailer::STRATEGY_SECURE => $this->strategySecure(
                        $defaultEmailChangeService,
                        $secureEmailChangeService,
                        $email,
                        $account,
                    ),
                    default => throw new OutOfBoundsException('Invalid email changing strategy.'),
                };
            }
        }

        return $this->viewRenderer
            ->withViewPath('@user/storage/view')
            ->render(
                'email-change',
                ['body' => $body, 'formModel' => $emailChangeForm, 'translator' => $this->translator],
            );
    }

    /**
     * @throws Exception|InvalidArgumentException|StaleObjectException|Throwable
     */
    private function strategySecure(
        DefaultEmailChangeService $defaultEmailChangeService,
        SecureEmailChangeService $secureEmailChangeService,
        string $email,
        Account $account
    ): void {
        $defaultEmailChangeService->run($email, $account);
        $secureEmailChangeService->run($account);
    }
}
