<?php

declare(strict_types=1);

namespace Forge\User\Module;

interface ModuleInterface
{
    /**
     * Returns new instance of the module with category translator.
     *
     * @param string $value The category translator.
     *
     * @return self
     */
    public function categoryTranslator(string $value): self;

    /**
     * Return new instance of Module with enabled confirm email.
     *
     * @param bool $value Whether to enable confirm email.
     *
     * @return self
     */
    public function enableConfirmEmail(bool $value): self;

    /**
     * Return new instance of Module with enabled generating password.
     *
     * @param bool $value Whether to enable generating password.
     *
     * @return self
     */
    public function enableGeneratePassword(bool $value): self;

    /**
     * Return new instance of Module with enabled login case sensitive.
     *
     * @param bool $value Whether to enable login case sensitive.
     *
     * @return self
     */
    public function enableLoginCaseSensitive(bool $value): self;

    /**
     * Return new instance of Module with enabled password recovery.
     *
     * @param bool $value Whether to enable password recovery.
     *
     * @return self
     */
    public function enablePasswordRecovery(bool $value): self;

    /**
     * Return new instance of Module with enabled registration.
     *
     * @param bool $value Whether to enable registration.
     *
     * @return self
     */
    public function enableRegister(bool $value): self;

    /**
     * Return new instance of Module with enabled remember me.
     *
     * @param bool $value Whether to enable remember me.
     *
     * @return self
     */
    public function enableRememberMe(bool $value): self;

    /**
     * Return name of module.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return regex expression for login.
     *
     * @return string
     */
    public function getRegexExpLogin(): string;

    /**
     * The time before a confirmation token becomes invalid.
     *
     * @return int
     */
    public function getTokenConfirm(): int;

    /**
     * The time before a recovery token becomes invalid.
     *
     * @return int
     */
    public function getTokenRecover(): int;

    /**
     * Return when enable or disable confirmation email.
     *
     * @return bool
     */
    public function isConfirmEmail(): bool;

    /**
     * Return when enable or disable generating password.
     *
     * @return bool
     */
    public function isGeneratePassword(): bool;

    /**
     * Return when enable or disable login case sensitive.
     *
     * @return bool
     */
    public function isLoginCaseSensitive(): bool;

    /**
     * Return when enable or disable password recovery.
     *
     * @return bool
     */
    public function isPasswordRecovery(): bool;

    /**
     * Return when enable or disable registration.
     *
     * @return bool
     */
    public function isRegister(): bool;

    /**
     * Return when enable or disable remember me.
     *
     * @return bool
     */
    public function isRememberMe(): bool;

    /**
     * Return new instance of Module with ModuleMailer instance.
     *
     * @return ModuleMailer
     */
    public function mailer(): ModuleMailerInterface;

    /**
     * Return new instance of Module with name.
     *
     * @param string $value Name of module.
     *
     * @return self
     */
    public function name(string $value): self;

    /**
     * Return new instance of Module with regex expression for login.
     *
     * @param string $value Regex expression for login.
     *
     * @return self
     */
    public function regexExpLogin(string $value): self;

    /**
     * Return new instance of Module with time token confirm.
     *
     * @param int $value Time token confirm.
     *
     * @return self
     */
    public function timeTokenConfirm(int $value): self;

    /**
     * Return new instance of Module with time token recover.
     *
     * @param int $value Time token recover.
     *
     * @return self
     */
    public function timeTokenRecover(int $value): self;
}
