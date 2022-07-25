<?php

declare(strict_types=1);

namespace Forge\User\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Yii\Db\Migration\MigrationBuilder;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M211126112600User
 */
final class M211126112600Account implements RevertibleMigrationInterface
{
    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $tableOptions = null;

        if ($b->getDb()->getName() === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 ENGINE=InnoDB';
        }

        $b->createTable(
            '{{%account}}',
            [
                'identity_id' => $b->primaryKey()->notNull()->unsigned() . ' REFERENCES identity(id) ON DELETE CASCADE',
                'username' => $b->string(255)->defaultValue('')->notNull(),
                'email' => $b->string(255)->defaultValue('')->notNull(),
                'password_hash' => $b->string(100)->defaultValue('')->notNull(),
                'confirmed_at' => $b->integer()->defaultValue(0),
                'unconfirmed_email' => $b->string(255)->defaultValue(''),
                'blocked_at' => $b->integer()->defaultValue(0),
                'registration_ip' => $b->string(45)->defaultValue(''),
                'created_at' => $b->integer()->defaultValue(0),
                'updated_at' => $b->integer()->defaultValue(0),
                'flags' => $b->integer()->defaultValue(0),
                'ip_last_login' => $b->string(45)->defaultValue(''),
                'last_login_at' => $b->integer()->defaultValue(0),
                'last_logout_at' => $b->integer()->defaultValue(0),
            ],
            $tableOptions
        );

        $b->createIndex('user_unique_email', '{{%account}}', ['email'], 'UNIQUE');
        $b->createIndex('user_unique_username', '{{%account}}', ['username'], 'UNIQUE');
    }

    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('{{%account}}');
    }
}
