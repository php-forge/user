<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\Service\Mailer;
use Forge\User\ActiveRecord\Account;
use Forge\User\Module\ModuleInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Translator\TranslatorInterface;

final class SecureEmailChangeService
{
    public function __construct(
        private Flash $flash,
        private Mailer $mailer,
        private ModuleInterface $module,
        private TokenToUrlService $tokenToUrlService,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * @throws Exception|StaleObjectException|Throwable
     */
    public function run(Account $account): void
    {
        $email = $account->getEmail();
        $params = [
            'email' => $email,
            'moduleName' => $this->module->getName(),
            'username' => $account->getUsername(),
            'url' => $this->tokenToUrlService->run(
                $account->getIdentityId(),
                TokenToUrlService::TYPE_CONFIRM_OLD_EMAIL,
            ),
        ];

        if (
            $this->mailer
                ->layout($this->module->mailer()->getLayoutEmailChange())
                ->subject($this->module->mailer()->getSubjectEmailChange())
                ->send($email, $params)
        ) {
            $message = $this->translator->translate(
                'secure.email.change.service.success',
                ['email' => $account->getEmail(), 'newEmail' => $account->getUnconfirmedEmail()],
            );

            $this->flash->add('success', ['content' => $message, 'type' => 'success']);
        }

        // unset flags if they exist
        $account->flags &= ~Account::NEW_EMAIL_CONFIRMED;
        $account->flags &= ~Account::OLD_EMAIL_CONFIRMED;
        $account->update();
    }
}
