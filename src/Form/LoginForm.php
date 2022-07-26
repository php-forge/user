<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Closure;
use Forge\FormValidator\Error\RequiredError;
use Forge\FormValidator\FormValidator;
use Forge\User\Module\ModuleInterface;
use Forge\User\Service\LoginService;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Required;

final class LoginForm extends FormValidator
{
    use RequiredError;

    private string $login = '';
    private string $password = '';
    private bool $rememberMe = false;
    private string $ip = '';
    private int $lastLogout = 0;

    public function __construct(
        private LoginService $loginService,
        private ModuleInterface $module,
        private TranslatorInterface $translator
    ) {
        parent::__construct();
    }

    public function getLabels(): array
    {
        return $this->loginTranslate();
    }

    public function getLastLogout(): int
    {
        return $this->lastLogout;
    }

    public function getPlaceholders(): array
    {
        return $this->loginTranslate();
    }

    public function getRules(): array
    {
        $required = new Required(message: $this->getRequiredErrorMessage());

        return [
            'login' => [$required],
            'password' => [$required, $this->passwordRules()],
        ];
    }

    public function ip(string $value): void
    {
        $this->ip = $value;
    }

    /**
     * @psalm-return string[]
     */
    private function loginTranslate(): array
    {
        return [
            'login' => $this->translator->translate('form.attribute.username'),
            'password' => $this->translator->translate('form.attribute.password'),
            'rememberMe' => $this->translator->translate('form.attribute.rememberMe'),
        ];
    }

    private function passwordRules(): Closure
    {
        return function (): Result {
            $result = $this->loginService->run(
                $this->login,
                $this->password,
                $this->ip,
                $this->module,
                $this->translator,
            );

            if ($result->isValid()) {
                $this->lastLogout = $this->loginService->getAccountLastLogout();
            }

            return $result;
        };
    }
}
