<?php

declare(strict_types=1);

namespace Forge\User\ActiveRecord;

use RuntimeException;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

/**
 * Token Active Record - Module AR Account.
 *
 * Database fields:
 *
 * @property int $identity_id
 * @property string $code
 * @property int $created_at
 * @property int $type
 * @property string $url
 * @property bool $isExpired
 *
 * Defined relations:
 * @property Identity|null $identity
 */
final class Token extends ActiveRecord
{
    public const TYPE_CONFIRMATION = 0;
    public const TYPE_RECOVERY = 1;
    public const TYPE_CONFIRM_NEW_EMAIL = 2;
    public const TYPE_CONFIRM_OLD_EMAIL = 3;

    public static function tableName(): string
    {
        return '{{%token}}';
    }

    public function getIndetity(): ActiveQueryInterface
    {
        return $this->hasOne(Identity::class, ['id' => 'identity_id']);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getUserId(): string
    {
        return (string)$this->identity_id;
    }

    public function isExpired(int $tokenConfirmWithin = 0, int $tokenRecoverWithin = 0): bool
    {
        $expirationTime = match ($this->getAttribute('type')) {
            self::TYPE_CONFIRMATION, self::TYPE_CONFIRM_NEW_EMAIL, self::TYPE_CONFIRM_OLD_EMAIL => $tokenConfirmWithin,
            self::TYPE_RECOVERY => $tokenRecoverWithin,
            default => throw new RuntimeException('Expired not available.'),
        };

        return ($this->created_at + $expirationTime) < time();
    }

    public function primaryKey(): array
    {
        return ['identity_id', 'code', 'type'];
    }
}
