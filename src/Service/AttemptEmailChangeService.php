<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Token;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Repository\TokenRepository;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Translator\TranslatorInterface;

final class AttemptEmailChangeService
{
    public function __construct(
        private AccountRepository $accountRepository,
        private Flash $flash,
        private ModuleInterface $module,
        private TokenRepository $tokenRepository,
        private TranslatorInterface $translator
    ) {
        $this->translator = $translator->withCategory('user');
    }

    /**
     * @throws Exception|InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(string $code, Account $account): bool
    {
        $result = true;

        $emailChangeStrategy = $this->module->mailer()->getEmailStrategy();
        $tokenConfirmWithin = $this->module->getTokenConfirm();
        $tokenRecoverWithin = $this->module->getTokenRecover();

        /** @var Token|null $token */
        $token = $this->tokenRepository->findByWhereCondition(
            [
                'identity_id' => $account->getIdentityId(),
                'code' => $code,
            ]
        )->andWhere(['IN', 'type', [Token::TYPE_CONFIRM_NEW_EMAIL, Token::TYPE_CONFIRM_OLD_EMAIL]])->one();

        if ($token === null || $token->isExpired($tokenConfirmWithin, $tokenRecoverWithin)) {
            $this->flash->add(
                'forge.user',
                [
                    'content' => $this->translator->translate('attempt.email.change.service.token_expired'),
                    'type' => 'danger',
                ],
            );

            $result = false;
        }

        if ($token !== null && $this->accountRepository->findByEmail($account->getUnconfirmedEmail()) === null) {
            $token->delete();

            if ($emailChangeStrategy === Account::STRATEGY_SECURE) {
                if ($token->getType() === Token::TYPE_CONFIRM_NEW_EMAIL) {
                    $account->flags |= Account::NEW_EMAIL_CONFIRMED;
                }

                if ($token->getType() === Token::TYPE_CONFIRM_OLD_EMAIL) {
                    $account->flags |= Account::OLD_EMAIL_CONFIRMED;
                }
            }

            if (
                $emailChangeStrategy === Account::STRATEGY_DEFAULT ||
                ($account->flags & Account::NEW_EMAIL_CONFIRMED) && ($account->flags & Account::OLD_EMAIL_CONFIRMED)
            ) {
                $account->email($account->getUnconfirmedEmail());
                $account->unconfirmedEmail('');
                $this->flash->add(
                    'forge.user',
                    [
                        'content' => $this->translator->translate('attempt.email.change.service.success'),
                        'type' => 'info',
                    ],
                );
            }

            $result = $account->save();
        }

        return $result;
    }
}
