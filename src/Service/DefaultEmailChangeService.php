<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\Service\Mailer;
use Forge\User\ActiveRecord\Account;
use Forge\User\Module\ModuleInterface;
use Throwable;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Translator\TranslatorInterface;

final class DefaultEmailChangeService
{
    public function __construct(
        private Flash $flash,
        private Mailer $mailer,
        private ModuleInterface $module,
        private TokenToUrlService $tokenToUrlService,
        private TranslatorInterface $translator
    ) {
        $this->translator = $translator->withCategory('user');
    }

    /**
     * @throws InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(string $email, Account $account): void
    {
        $account->unconfirmedEmail($email);
        $email = $account->getUnconfirmedEmail();
        $result = (bool) $account->update();

        if ($result) {
            $params = [
                'email' => $email,
                'moduleName' => $this->module->getName(),
                'username' => $account->getUsername(),
                'url' => $this->tokenToUrlService->run(
                    $account->getIdentityId(),
                    TokenToUrlService::TYPE_CONFIRM_NEW_EMAIL,
                ),
            ];

            if (
                $this->mailer
                    ->layout($this->module->mailer()->getLayoutEmailChange())
                    ->subject($this->module->mailer()->getSubjectEmailChange())
                    ->send($email, $params)
            ) {
                $message = $this->translator->translate(
                    'default.service.email.change.success',
                    ['email' => $account->getEmail()],
                );
                $this->flash->add('forge.user', ['content' => $message, 'type' => 'success']);
            }
        }
    }
}
