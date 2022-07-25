<?php

declare(strict_types=1);

namespace Forge\User\Module;

use Yiisoft\Translator\TranslatorInterface;

final class ModuleMailer implements ModuleMailerInterface
{
    /** Email is changed right after user enter's new email address. */
    public const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    public const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    public const STRATEGY_SECURE = 2;

    private int $emailStrategy = self::STRATEGY_DEFAULT;
    private array $layoutEmailChange = ['html' => 'email-change', 'text' => 'text/email-change'];
    private array $layoutRegister = ['html' => 'welcome', 'text' => 'text/welcome'];
    private array $layoutRequest =  ['html' => 'recovery', 'text' => 'text/recovery'];
    private array $layoutResend = ['html' => 'confirmation', 'text' => 'text/confirmation'];
    private string $subjectEmailChange = 'mailer.email.change';
    private string $subjectRegister = 'mailer.welcome';
    private string $subjectRequest = 'mailer.request';
    private string $subjectResend = 'mailer.resend';

    public function __construct(private string $name, private TranslatorInterface $translator)
    {
    }

    public function emailStrategy(int $value): self
    {
        $new = clone $this;
        $new->emailStrategy = $value;

        return $new;
    }

    public function getEmailStrategy(): int
    {
        return $this->emailStrategy;
    }

    public function getLayoutEmailChange(): array
    {
        return $this->layoutEmailChange;
    }

    public function getLayoutRegister(): array
    {
        return $this->layoutRegister;
    }

    public function getLayoutRequest(): array
    {
        return $this->layoutRequest;
    }

    public function getLayoutResend(): array
    {
        return $this->layoutResend;
    }

    public function getSubjectEmailChange(): string
    {
        return $this->translator->translate($this->subjectEmailChange, ['moduleName' => $this->name]);
    }

    public function getSubjectRegister(): string
    {
        return $this->translator->translate($this->subjectRegister, ['moduleName' => $this->name]);
    }

    public function getSubjectRequest(): string
    {
        return $this->translator->translate($this->subjectRequest, ['moduleName' => $this->name]);
    }

    public function getSubjectResend(): string
    {
        return $this->translator->translate($this->subjectResend, ['moduleName' => $this->name]);
    }

    public function layoutRegister(string $html, string $text): self
    {
        $new = clone $this;
        $new->layoutRegister = ['html' => $html, 'text' => $text];

        return $new;
    }

    public function subjectRegister(string $value): self
    {
        $new = clone $this;
        $new->subjectRegister = $value;

        return $new;
    }
}
