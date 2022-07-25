<?php

declare(strict_types=1);

namespace Forge\User\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Yii\Db\Migration\MigrationBuilder;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M211126112850SocialAccount
 */
final class M211126112850SocialAccount implements RevertibleMigrationInterface
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
            '{{%social_account}}',
            [
                'identity_id' => $b->primaryKey()->notNull()->unsigned() . ' REFERENCES identity(id) ON DELETE CASCADE',
                'user_id' => $b->integer()->defaultValue(0),
                'provider' => $b->string(255)->defaultValue(''),
                'client_id' => $b->string(255)->defaultValue(''),
                'data' => $b->text()->defaultValue(''),
                'code' => $b->string(32)->defaultValue(''),
                'created_at' => $b->integer()->defaultValue(0),
                'email' => $b->string(255)->defaultValue(''),
                'username' => $b->string(255)->defaultValue(''),
            ],
            $tableOptions
        );

        $b->createIndex('account_unique', '{{%social_account}}', ['provider', 'client_id'], 'UNIQUE');
        $b->createIndex('account_unique_code', '{{%social_account}}', ['code'], 'UNIQUE');
    }

    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('{{%social_account}}');
    }
}
