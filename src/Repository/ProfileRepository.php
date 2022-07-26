<?php

declare(strict_types=1);

namespace Forge\User\Repository;

use Forge\User\ActiveRecord\Profile;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Definitions\Exception\InvalidConfigException;

final class ProfileRepository
{
    private ActiveQueryInterface $activeQuery;

    /**
     * @throws InvalidConfigException
     */
    public function __construct(ActiveRecordFactory $activeRecordFactory)
    {
        $this->activeQuery = $activeRecordFactory->createQueryTo(Profile::class);
    }

    public function findById(string $id): ?ActiveRecordInterface
    {
        return $this->findByOneCondition(['identity_id' => (int)$id]);
    }

    public function findByOneCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->activeQuery->findOne($condition);
    }
}
