<?php

declare(strict_types=1);

namespace Forge\User\Repository;

use Forge\User\ActiveRecord\Account;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Definitions\Exception\InvalidConfigException;

use function filter_var;

final class AccountRepository
{
    private ActiveQueryInterface $activeQuery;

    /**
     * @throws InvalidConfigException
     */
    public function __construct(ActiveRecordFactory $activeRecordFactory)
    {
        $this->activeQuery = $activeRecordFactory->createQueryTo(Account::class);
    }

    public function findByEmail(string $email): ?ActiveRecordInterface
    {
        return $this->findByOneCondition(['email' => $email]);
    }

    public function findById(string $id): ?ActiveRecordInterface
    {
        return $this->findByOneCondition(['identity_id' => (int)$id]);
    }

    public function findByUsername(string $username): ?ActiveRecordInterface
    {
        return $this->findByOneCondition(['username' => $username]);
    }

    public function findByUsernameOrEmail(string $usernameOrEmail): ?ActiveRecordInterface
    {
        return filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)
            ? $this->findByEmail($usernameOrEmail)
            : $this->findByUsername($usernameOrEmail);
    }

    public function findByOneCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->activeQuery->findOne($condition);
    }
}
