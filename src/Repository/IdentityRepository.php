<?php

declare(strict_types=1);

namespace Forge\User\Repository;

use Forge\User\ActiveRecord\Identity;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Definitions\Exception\InvalidConfigException;

final class IdentityRepository implements IdentityRepositoryInterface
{
    private ActiveQueryInterface $activeQuery;

    /**
     * @throws InvalidConfigException
     */
    public function __construct(ActiveRecordFactory $activeRecordFactory)
    {
        $this->activeQuery = $activeRecordFactory->createQueryTo(Identity::class);
    }

    /**
     * @param string $id
     *
     * @return IdentityInterface|null
     */
    public function findIdentity(string $id): ?IdentityInterface
    {
        $identity = $this->activeQuery->findOne(['id' => $id]);

        return $identity instanceof Identity ? $identity : null;
    }
}
