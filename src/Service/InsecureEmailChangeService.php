<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\User\ActiveRecord\Account;
use Throwable;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Translator\TranslatorInterface;

final class InsecureEmailChangeService
{
    public function __construct(private Flash $flash, private TranslatorInterface $translator)
    {
    }

    /**
     * @throws InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(string $email, Account $account): void
    {
        $account->email($email);

        $result = (bool) $account->update();

        if ($result) {
            $this->flash->add(
                'forge.user',
                [
                    'content' => $this->translator->translate('insecure.email.change.service.success'),
                    'type' => 'success',
                ],
            );
        }
    }
}
