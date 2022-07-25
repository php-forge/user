<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Forge\FormValidator\Error;
use Forge\FormValidator\FormValidator;
use Forge\User\ActiveRecord\Account;
use Forge\User\Repository\AccountRepository;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Required;

final class EmailChangeForm extends FormValidator
{
    use Error\EmailError;
    use Error\RequiredError;

    private string $email = '';
    private string $oldEmail = '';

    public function __construct(
        private Account $account,
        private AccountRepository $accountRepository,
        private TranslatorInterface $translator
    ) {
        parent::__construct();

        $this->validateEmail();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLabels(): array
    {
        return $this->emailChangeTranslate();
    }

    public function getPlaceholders(): array
    {
        return $this->emailChangeTranslate();
    }

    public function getRules(): array
    {
        return [
            'email' => $this->emailRules(),
        ];
    }

    /**
     * @psalm-return string[]
     */
    private function emailChangeTranslate(): array
    {
        return [
            'email' => $this->translator->translate('form.attribute.email'),
            'oldEmail' => $this->translator->translate('form.attribute.old.email'),
        ];
    }

    private function emailRules(): array
    {
        return [
            new Required(message: $this->getRequiredErrorMessage()),
            new Email(message: $this->getEmailErrorMessage()),
            function (): Result {
                $result = new Result();

                /** @var Account|null */
                $account = $this->accountRepository->findByUsernameOrEmail($this->email);

                if ($account !== null) {
                    $result->addError($this->translator->translate('validator.email.already.exists'));
                }

                return $result;
            },
        ];
    }

    private function validateEmail(): void
    {
        $this->email = $this->account->getEmail();

        if ($this->account->getUnconfirmedEmail() !== '') {
            $this->email = $this->account->getUnconfirmedEmail();
            $this->error()->add('email', $this->translator->translate('validator.email.not.confirmed'));
        }

        $this->oldEmail = $this->account->getEmail();
    }
}
