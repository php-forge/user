<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Identity;
use Forge\User\ActiveRecord\Profile;
use Forge\User\Form\RegisterForm;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;
use Throwable;
use Yii\Extension\Helpers\Password;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\Db\Connection\Connection;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

final class UserRegisterService
{
    public function __construct(
        private ActiveRecordFactory $activeRecordFactory,
        private ConnectionInterface $db,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws Exception|InvalidConfigException|NotSupportedException|Throwable
     */
    public function run(RegisterForm $registerForm, bool $isConfirmEmail, bool $isGeneratePassword): bool
    {
        /** @var Identity $identity */
        $identity = $this->activeRecordFactory->createAR(Identity::class);

        /** @var Profile $profile */
        $profile = $this->activeRecordFactory->createAR(Profile::class);

        /** @var Account $account */
        $account = $this->activeRecordFactory->createAR(Account::class);

        if ($identity->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        /** @var Connection $db */
        $db = $this->db;
        $transaction = $db->beginTransaction();

        try {
            if (!$identity->save()) {
                $transaction->rollBack();
                return false;
            }

            $account->link('identity', $identity);

            $password = $registerForm->getPassword();

            if ($isGeneratePassword) {
                $password = Password::generate(8);
                $registerForm->password($password);
            }

            $account->username($registerForm->getUsername());
            $account->email($registerForm->getEmail());
            $account->unconfirmedEmail('');
            $account->password($password);
            $account->passwordHash($password);
            $account->registrationIp($registerForm->getIp());

            if ($isConfirmEmail === false) {
                $account->confirmedAt();
            }

            $account->createdAt();
            $account->updatedAt();
            $account->flags(0);

            $account->save();

            $profile->link('identity', $identity);

            $transaction->commit();

            $registerForm->setValue('userId', $identity->getId());
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }

        return true;
    }
}
