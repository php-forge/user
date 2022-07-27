<?php

declare(strict_types=1);

namespace Forge\User\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Security\PasswordHasher;

/**
 * Account represents the data structure for account object.
 *
 * Database fields:
 *
 * @property int $identity_id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $registration_ip
 * @property int $confirmed_at
 * @property int $blocked_at
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_login_at
 * @property int $last_logout_at
 * @property int $flags
 *
 * Defined relations:
 * @property Identity|null $identity
 */
final class Account extends ActiveRecord
{
    /** Following constants are used on secured email changing process */
    public const NEW_EMAIL_CONFIRMED = 0b10;
    public const OLD_EMAIL_CONFIRMED = 0b1;
    private string $password = '';

    public static function tableName(): string
    {
        return '{{%account}}';
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== 0;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed_at !== 0;
    }

    public function getIdentity(): ActiveQueryInterface
    {
        return $this->hasOne(Identity::class, ['id' => 'identity_id']);
    }

    public function getIdentityId(): string
    {
        return (string)$this->identity_id;
    }

    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    public function getConfirmedAt(): int
    {
        return $this->confirmed_at;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLastLogout(): int
    {
        return $this->last_logout_at;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getRegistrationIp(): string
    {
        return $this->registration_ip;
    }

    public function getUnconfirmedEmail(): string
    {
        return $this->unconfirmed_email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function username(string $value): void
    {
        $this->setAttribute('username', $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function email(string $value): void
    {
        $this->setAttribute('email', $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function unconfirmedEmail(string $value): void
    {
        $this->setAttribute('unconfirmed_email', $value);
    }

    public function password(string $value): void
    {
        $this->password = $value;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function passwordHash(string $value): void
    {
        $this->setAttribute('password_hash', (new PasswordHasher(PASSWORD_ARGON2I))->hash($value));
    }

    /**
     * @throws Exception|NotSupportedException
     */
    public function passwordHashUpdate(string $value): void
    {
        $this->updateAttributes(['password_hash' => (new PasswordHasher(PASSWORD_ARGON2I))->hash($value)]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function registrationIp(string $value): void
    {
        $this->setAttribute('registration_ip', $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function confirmedAt(): void
    {
        $this->setAttribute('confirmed_at', time());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function createdAt(): void
    {
        $this->setAttribute('created_at', time());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function updatedAt(): void
    {
        $this->setAttribute('updated_at', time());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function flags(int $value): void
    {
        $this->setAttribute('flags', $value);
    }

    public function validatePassword(string $password): bool
    {
        return (new PasswordHasher())->validate($password, $this->password_hash);
    }
}
