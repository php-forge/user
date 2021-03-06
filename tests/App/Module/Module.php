<?php

declare(strict_types=1);

namespace Forge\User\Tests\App\Module;

use Forge\User\Module\ModuleInterface;
use Forge\User\Module\ModuleMailerInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Translator\TranslatorInterface;

/**
 * Module config active record.
 *
 * Database fields:
 *
 * @property int     $id
 * @property bool    $confirmation
 * @property bool    $delete
 * @property bool    $generatingPassword
 * @property string  $messageHeader
 * @property bool    $passwordRecovery
 * @property bool    $register
 * @property bool    $rememberMe
 * @property int     $tokenConfirmWithin
 * @property int     $tokenRecoverWithin
 * @property bool    $userNameCaseSensitive
 * @property string  $userNameRegExp
 * @property int     $emailChangeStrategy
 */
final class Module extends ActiveRecord implements ModuleInterface
{
    private string $categoryTranslator = 'user';
    private ModuleMailer $moduleMailer;
    private string $name = 'module.name';

    public function __construct(ConnectionInterface $db, private TranslatorInterface $translator)
    {
        parent::__construct($db);

        $this->translator = $translator->withCategory($this->categoryTranslator);
    }

    public static function tableName(): string
    {
        return '{{%settings}}';
    }

    public function categoryTranslator(string $value): self
    {
        $new = clone $this;
        $new->categoryTranslator = $value;

        return $new;
    }

    public function enableConfirmEmail(bool $value): self
    {
        $this->setAttribute('confirmation', $value);

        return $this;
    }

    public function enableGeneratePassword(bool $value): self
    {
        $this->setAttribute('generatingPassword', $value);

        return $this;
    }

    public function enableLoginCaseSensitive(bool $value): self
    {
        $this->setAttribute('userNameCaseSensitive', $value);

        return $this;
    }

    public function enablePasswordRecovery(bool $value): self
    {
        $this->setAttribute('passwordRecovery', $value);

        return $this;
    }

    public function enableRegister(bool $value): self
    {
        $this->setAttribute('register', $value);

        return $this;
    }

    public function enableRememberMe(bool $value): self
    {
        $this->setAttribute('remember_me', $value);

        return $this;
    }

    public function getName(): string
    {
        return $this->translator->translate($this->name);
    }

    public function getRegexExpLogin(): string
    {
        return $this->getAttribute('userNameRegExp');
    }

    public function getTokenConfirm(): int
    {
        return $this->getAttribute('tokenConfirmWithin');
    }

    public function getTokenRecover(): int
    {
        return $this->getAttribute('tokenRecoverWithin');
    }

    public function isConfirmEmail(): bool
    {
        return $this->getAttribute('confirmation');
    }

    public function isGeneratePassword(): bool
    {
        return $this->getAttribute('generatingPassword');
    }

    public function isLoginCaseSensitive(): bool
    {
        return $this->getAttribute('userNameCaseSensitive');
    }

    public function isPasswordRecovery(): bool
    {
        return $this->getAttribute('passwordRecovery');
    }

    public function isRegister(): bool
    {
        return $this->getAttribute('register');
    }

    public function isRememberMe(): bool
    {
        return $this->getAttribute('remember_me');
    }

    public function mailer(): ModuleMailerInterface
    {
        return new ModuleMailer(
            $this->getAttribute('emailChangeStrategy'),
            $this->getName(),
            $this->translator,
        );
    }

    public function name(string $value): self
    {
        $new = clone $this;
        $new->name = $value;

        return $new;
    }

    public function regexExpLogin(string $value): self
    {
        $this->setAttribute('userNameRegExp', $value);

        return $this;
    }

    public function timeTokenConfirm(int $value): self
    {
        $this->setAttribute('tokenConfirmWithin', $value);

        return $this;
    }

    public function timeTokenRecover(int $value): self
    {
        $this->setAttribute('tokenRecoverWithin', $value);

        return $this;
    }
}
