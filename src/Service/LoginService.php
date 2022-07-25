<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\User\Module\ModuleInterface;
use Forge\User\ActiveRecord\Account;
use Forge\User\Repository\AccountRepository;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Validator\Result;

final class LoginService
{
    private int $lastLogout = 0;

    public function __construct(private AccountRepository $accountRepository, private CurrentUser $currentUser)
    {
    }

    public function run(
        string $login,
        string $password,
        string $ip,
        ModuleInterface $module,
        TranslatorInterface $translator
    ): Result {
        if (!$module->isLoginCaseSensitive()) {
            $login = strtolower($login);
        }

        /** @var Account|null $account */
        $account = $this->accountRepository->findByUsernameOrEmail($login);
        $result = new Result();

        // Check if account exists.
        if ($account === null) {
            $result->addError($translator->translate('validator.invalid.credentials'));
            return $result;
        }

        // Check if account is confirmed.
        if (!$account->isConfirmed()) {
            $result->addError($translator->translate('validator.account.not.confirmed'));
        }

        // Check if account is blocked.
        if ($account->isBlocked()) {
            $result->addError($translator->translate('validator.account.blocked'));
        }

        // Check password.
        if ('' !== $password && !$account->validatePassword($password)) {
            $result->addError($translator->translate('validator.invalid.credentials'));
        }

        if ($result->isValid() && ($identity = $account->identity) !== null) {
            $this->lastLogout = $account->getLastLogout();
            $this->currentUser->login($identity);

            $account->updateAttributes(['ip_last_login' => $ip, 'last_login_at' => time()]);
        }

        return $result;
    }

    public function getAccountLastLogout(): int
    {
        return $this->lastLogout;
    }

    public function getIdentity(): ?IdentityInterface
    {
        return $this->currentUser->getIdentity();
    }
}
