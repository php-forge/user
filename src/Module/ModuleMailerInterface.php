<?php

declare(strict_types=1);

namespace Forge\User\Module;

interface ModuleMailerInterface
{
    /**
     * Return new instance of ModuleMailer When user tries change his password, there are three ways how this change
     * will happen:
     *
     * STRATEGY_DEFAULT This is default strategy. Confirmation message will be sent to new user's email and user must
     * click confirmation link.
     *
     * STRATEGY_INSECURE Email will be changed without any confirmation.
     *
     * STRATEGY_SECURE Confirmation messages will be sent to both new and old user's email addresses and user must click
     * both confirmation links.
     *
     * @param int $strategy Email change strategy.
     *
     * @return self
     */
    public function emailStrategy(int $value): self;

    /**
     * Return when email is changed right after user enter's new email address.
     *
     * @return int
     */
    public function getEmailStrategy(): int;

    /**
     * Return layout for email change.
     *
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getLayoutEmailChange(): array;

    /**
     * Return layout for registration.
     *
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getLayoutRegister(): array;

    /**
     * Return layout for request.
     *
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getLayoutRequest(): array;


    /**
     * Return layout for resend.
     *
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getLayoutResend(): array;

    /**
     * Return subject for registration.
     *
     * @return string
     */
    public function getSubjectRegister(): string;

    /**
     * Return subject for request.
     *
     * @return string
     */
    public function getSubjectRequest(): string;

    /**
     * Return subject for resend.
     *
     * @return string
     */
    public function getSubjectResend(): string;

    /**
     * Return new instance of ModuleMailer with layout for registration.
     *
     * @param string $html HTML layout.
     * @param string $text Text layout.
     *
     * @return self
     */
    public function layoutRegister(string $html, string $text): self;

    /**
     * Return new instance of ModuleMailer with layout for request.
     *
     * @param string $html HTML layout.
     * @param string $text Text layout.
     *
     * @return self
     */
    public function layoutRequest(string $html, string $text): self;

    /**
     * Return new instance of ModuleMailer with layout for resend.
     *
     * @param string $html HTML layout.
     * @param string $text Text layout.
     *
     * @return self
     */
    public function layoutResend(string $html, string $text): self;

    /**
     * Return new instance of ModuleMailer with subject for registration.
     *
     * @param string $value Subject.
     *
     * @return self
     */
    public function subjectRegister(string $value): self;
}
