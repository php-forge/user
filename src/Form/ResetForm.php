<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Closure;
use Forge\FormValidator\Error;
use Forge\FormValidator\FormValidator;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

final class ResetForm extends FormValidator
{
    use Error\HasLengthError;
    use Error\RequiredError;

    private string $password = '';
    private string $passwordVerify = '';

    public function __construct(private TranslatorInterface $translator)
    {
        parent::__construct();
    }

    public function getLabels(): array
    {
        return $this->resetTranslate();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPlaceholders(): array
    {
        return $this->resetTranslate();
    }

    public function getRules(): array
    {
        $required = new Required(message: $this->getRequiredErrorMessage());

        return [
            'password' => [
                $required,
                new HasLength(
                    min: 6,
                    max: 72,
                    message: $this->getHasLengthErrorMessge(),
                    lessThanMinMessage: $this->getHasLengthTooShortErrorMessage(6, $this->password),
                    greaterThanMaxMessage: $this->getHasLengthTooLongErrorMessage(72, $this->password),
                ),
            ],
            'passwordVerify' => [
                $required,
                new HasLength(
                    min: 6,
                    max: 72,
                    message: $this->getHasLengthErrorMessge(),
                    lessThanMinMessage: $this->getHasLengthTooShortErrorMessage(6, $this->password),
                    greaterThanMaxMessage: $this->getHasLengthTooLongErrorMessage(72, $this->password),
                ),
                $this->passwordVerifyRules(),
            ],
        ];
    }

    private function passwordVerifyRules(): Closure
    {
        return function (): Result {
            $result = new Result();

            if ($this->password !== $this->passwordVerify) {
                $result->addError(
                    $this->translator->translate('validator.password.verify.match')
                );
            }

            return $result;
        };
    }

    /**
     * @psalm-return string[]
     */
    private function resetTranslate(): array
    {
        return [
            'password' => $this->translator->translate('form.attribute.password'),
            'passwordVerify' => $this->translator->translate('form.attribute.password.verify'),
        ];
    }
}
