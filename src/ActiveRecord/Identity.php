<?php

declare(strict_types=1);

namespace Forge\User\ActiveRecord;

use Exception;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Security\Random;
use Yiisoft\User\Login\Cookie\CookieLoginIdentityInterface;

/**
 * Identity represents the data structure for an identity object.
 *
 * Database fields:
 *
 * @property int $id
 * @property string $auth_key
 *
 * Defined relations:
 * @property Account|null $account
 * @property Profile|null $profile
 * @property Token|null $token
 */
final class Identity extends ActiveRecord implements CookieLoginIdentityInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(ConnectionInterface $db, ActiveRecordFactory $arFactory)
    {
        parent::__construct($db, $arFactory);

        $this->regenerateCookieLoginKey();
    }

    public static function tableName(): string
    {
        return '{{%identity}}';
    }

    public function getId(): ?string
    {
        return (string)$this->id;
    }

    public function getCookieLoginKey(): string
    {
        return $this->auth_key;
    }

    public function getAccount(): ActiveQueryInterface
    {
        return $this->hasOne(Account::class, ['identity_id' => 'id']);
    }

    public function validateCookieLoginKey(string $key): bool
    {
        return $this->auth_key === $key;
    }

    /**
     * @throws Exception|InvalidArgumentException
     */
    public function regenerateCookieLoginKey(): void
    {
        $this->setAttribute('auth_key', Random::string());
    }
}
