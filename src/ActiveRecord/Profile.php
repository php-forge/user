<?php

declare(strict_types=1);

namespace Forge\User\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

/**
 * Profile Active Record - Module AR Account.
 *
 * Database fields:
 *
 * @property int $identity_id
 * @property string $name
 * @property string $public_email
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $timezone
 *
 * Defined relations:
 * @property Identity|null $identity
 */
final class Profile extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%profile}}';
    }

    public function getIdentity(): ActiveQueryInterface
    {
        return $this->hasOne(Identity::class, ['id' => 'identity_id']);
    }
}
