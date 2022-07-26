<?php

declare(strict_types=1);

namespace Forge\User\Repository;

use Forge\User\ActiveRecord\Token;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Db\Query\QueryInterface;
use Yiisoft\Definitions\Exception\InvalidConfigException;

final class TokenRepository
{
    private ActiveQueryInterface $activeQuery;

    /**
     * @throws InvalidConfigException
     */
    public function __construct(ActiveRecordFactory $activeRecordFactory)
    {
        $this->activeQuery = $activeRecordFactory->createQueryTo(Token::class);
    }

    public function findById(string $id): ?ActiveRecordInterface
    {
        return $this->findByOneCondition(['identity_id' => (int)$id]);
    }

    public function findByOneCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->activeQuery->findOne($condition);
    }

    public function findByWhereCondition(array $condition): QueryInterface
    {
        return $this->activeQuery->where($condition);
    }
}
