<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Closure;
use Forge\FormValidator\Error;
use Forge\FormValidator\FormValidator;
use Forge\User\ActiveRecord\Account;
use Forge\User\Repository\AccountRepository;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Required;

use function strtolower;

final class RequestForm extends FormValidator
{
    use Error\EmailError;
    use Error\RequiredError;

    private string $email = '';
    private string $userId = '';
    private string $username = '';

    public function __construct(private AccountRepository $accountRepository, private TranslatorInterface $translator)
    {
        parent::__construct();
    }

    public function getEmail(): string
    {
        return strtolower($this->email);
    }

    public function getLabels(): array
    {
        return $this->requestTranslate();
    }

    public function getPlaceholders(): array
    {
        return $this->requestTranslate();
    }

    public function getRules(): array
    {
        return [
            'email' => [
                new Required(message: $this->getRequiredErrorMessage()),
                new Email(message: $this->getEmailErrorMessage()),
                $this->emailRules(),
            ],
        ];
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    private function requestTranslate(): array
    {
        return [
            'email' => $this->translator->translate('form.attribute.email'),
        ];
    }

    private function emailRules(): Closure
    {
        return function (): Result {
            $result = new Result();

            /** @var Account|null $account */
            $account = $this->accountRepository->findByUsernameOrEmail($this->email);

            if ($account === null) {
                $result->addError($this->translator->translate('validator.email.not.found'));
            }

            if ($account !== null && !$account->isConfirmed()) {
                $result->addError($this->translator->translate('validator.email.not.confirmed'));
            }

            if ($result->isValid() && $account !== null) {
                $this->userId = $account->getIdentityId();
                $this->username = $account->getUsername();
            }

            return $result;
        };
    }
}
