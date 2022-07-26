<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Closure;
use Forge\FormValidator\Error;
use Forge\FormValidator\FormValidator;
use Forge\User\ActiveRecord\Account;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Stringable;
use Yii\Extension\Helpers\Password;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\Strings\Inflector;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

use function strtolower;

final class RegisterForm extends FormValidator
{
    use Error\EmailError;
    use Error\HasLengthError;
    use Error\RequiredError;

    private string $userId = '';
    private string $email = '';
    private string $username = '';
    private string $password = '';
    private string $passwordVerify = '';
    private string $ip = '';

    public function __construct(
        private AccountRepository $accountRepository,
        private ModuleInterface $module,
        private TranslatorInterface $translator
    ) {
        parent::__construct();
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getEmail(): string
    {
        return strtolower($this->email);
    }

    public function getLabels(): array
    {
        return $this->registerTranslate();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPlaceholders(): array
    {
        return $this->registerTranslate();
    }

    public function getRules(): array
    {
        $required = new Required(message: $this->getRequiredErrorMessage());

        return [
            'email' => [$required, new Email(message: $this->getEmailErrorMessage()), $this->emailRules()],
            'username' => [
                $required,
                new HasLength(
                    min: 3,
                    max:255,
                    message: $this->getHasLengthErrorMessge(),
                    lessThanMinMessage: $this->getHasLengthTooShortErrorMessage(3, $this->username),
                    greaterThanMaxMessage: $this->getHasLengthTooLongErrorMessage(255, $this->username),
                ),
                new Regex(pattern: $this->module->getRegexExpLogin()),
                $this->usernameRules(),
            ],
            'password' => $this->passwordRules(),
            'passwordVerify' => $this->passwordVerifyRules(),
        ];
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        if (!$this->module->isLoginCaseSensitive()) {
            $this->username = strtolower($this->username);
        }

        return $this->username;
    }

    public function ip(string $value): void
    {
        $this->ip = $value;
    }

    public function loadData(Account $account): void
    {
        $data = $account->getAttributes();

        unset($data['identity_id']);

        $this->setAttributes($data);
    }

    public function password(string $value): void
    {
        $this->password = $value;
    }

    public function setAttributes(array $data): void
    {
        /** @var array<string, object|scalar|Stringable|null> $data */
        foreach ($data as $name => $value) {
            $name = (new Inflector())->toCamelCase($name);

            if ($this->has($name)) {
                $this->setValue($name, $value);
            }
        }
    }

    /**
     * @throws Exception|NotSupportedException
     */
    public function update(Account $account): bool
    {
        $password = empty($this->password) ? Password::generate(8) : $this->password;

        return (bool) $account->updateAttributes(
            [
                'username' => $this->username,
                'email' > $this->email,
                'password_hash' => (new PasswordHasher(PASSWORD_ARGON2I))->hash($password),
                'register_ip' => $this->ip,
            ]
        );
    }

    private function emailRules(): Closure
    {
        return function (): Result {
            $result = new Result();

            if ($this->accountRepository->findByUsernameOrEmail($this->email)) {
                $result->addError(
                    $this->translator->translate('validator.email.already.exists')
                );
            }

            return $result;
        };
    }

    private function passwordRules(): array
    {
        $result = [];

        if ($this->module->isGeneratePassword() === false) {
            $result = [
                new Required(message: $this->getRequiredErrorMessage()),
                new HasLength(
                    min: 6,
                    max:72,
                    message: $this->getHasLengthErrorMessge(),
                    lessThanMinMessage: $this->getHasLengthTooShortErrorMessage(6, $this->password),
                    greaterThanMaxMessage: $this->getHasLengthTooLongErrorMessage(72, $this->password),
                ),
            ];
        }

        return $result;
    }

    private function passwordVerifyRules(): array
    {
        $result = [];

        if ($this->module->isGeneratePassword() === false) {
            $result = [
                new Required(message: $this->getRequiredErrorMessage()),

                function (): Result {
                    $result = new Result();

                    if ($this->password !== $this->passwordVerify) {
                        $result->addError(
                            $this->translator->translate('validator.password.verify.match')
                        );
                    }

                    return $result;
                },
            ];
        }

        return $result;
    }

    /**
     * @psalm-return string[]
     */
    private function registerTranslate(): array
    {
        return [
            'email' => $this->translator->translate('form.attribute.email'),
            'username' => $this->translator->translate('form.attribute.username'),
            'password' => $this->translator->translate('form.attribute.password'),
            'passwordVerify' => $this->translator->translate('form.attribute.password.verify'),
        ];
    }

    private function usernameRules(): Closure
    {
        return function (): Result {
            $result = new Result();

            if ($this->accountRepository->findByUsernameOrEmail($this->username)) {
                $result->addError($this->translator->translate('validator.username.already.exists'));
            }

            return $result;
        };
    }
}
