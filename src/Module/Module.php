<?php

declare(strict_types=1);

namespace Forge\User\Module;

use Yiisoft\Translator\TranslatorInterface;

final class Module implements ModuleInterface
{
    private string $categoryTranslator = 'user';
    private bool $enableConfirmEmail = false;
    private bool $enableGeneratePassword = false;
    private bool $enableLoginCaseSensitive = true;
    private bool $enablePasswordRecovery = true;
    private bool $enableRegister = true;
    private bool $enableRememberMe = true;
    private ModuleMailer $moduleMailer;
    private string $name = 'module.name';
    private int $timeTokenConfirm = 86400;
    private int $timeTokenRecover = 21600;
    private string $regexExpLogin = '/^[-a-zA-Z0-9_\.@]+$/';

    public function __construct(private TranslatorInterface $translator)
    {
        $this->translator = $translator->withCategory($this->categoryTranslator);
        $this->moduleMailer = new ModuleMailer($this->getName(), $this->translator);
    }

    public function categoryTranslator(string $value): self
    {
        $new = clone $this;
        $new->categoryTranslator = $value;

        return $new;
    }

    public function enableConfirmEmail(bool $value): self
    {
        $new = clone $this;
        $new->enableConfirmEmail = $value;

        return $new;
    }

    public function enableGeneratePassword(bool $value): self
    {
        $new = clone $this;
        $new->enableGeneratePassword = $value;

        return $new;
    }

    public function enableLoginCaseSensitive(bool $value): self
    {
        $new = clone $this;
        $new->enableLoginCaseSensitive = $value;

        return $new;
    }

    public function enablePasswordRecovery(bool $value): self
    {
        $new = clone $this;
        $new->enablePasswordRecovery = $value;

        return $new;
    }

    public function enableRegister(bool $value): self
    {
        $new = clone $this;
        $new->enableRegister = $value;

        return $new;
    }

    public function enableRememberMe(bool $value): self
    {
        $new = clone $this;
        $new->enableRememberMe = $value;

        return $new;
    }

    public function getName(): string
    {
        return $this->translator->translate($this->name);
    }

    public function getRegexExpLogin(): string
    {
        return $this->regexExpLogin;
    }

    public function getTokenConfirm(): int
    {
        return $this->timeTokenConfirm;
    }

    public function getTokenRecover(): int
    {
        return $this->timeTokenRecover;
    }

    public function isConfirmEmail(): bool
    {
        return $this->enableConfirmEmail;
    }

    public function isGeneratePassword(): bool
    {
        return $this->enableGeneratePassword;
    }

    public function isLoginCaseSensitive(): bool
    {
        return $this->enableLoginCaseSensitive;
    }

    public function isPasswordRecovery(): bool
    {
        return $this->enablePasswordRecovery;
    }

    public function isRegister(): bool
    {
        return $this->enableRegister;
    }

    public function isRememberMe(): bool
    {
        return $this->enableRememberMe;
    }

    public function mailer(): ModuleMailerInterface
    {
        return $this->moduleMailer;
    }

    public function name(string $value): self
    {
        $new = clone $this;
        $new->name = $value;

        return $new;
    }

    public function regexExpLogin(string $value): self
    {
        $new = clone $this;
        $new->regexExpLogin = $value;

        return $new;
    }

    public function timeTokenConfirm(int $value): self
    {
        $new = clone $this;
        $new->timeTokenConfirm = $value;

        return $new;
    }

    public function timeTokenRecover(int $value): self
    {
        $new = clone $this;
        $new->timeTokenRecover = $value;

        return $new;
    }
}
