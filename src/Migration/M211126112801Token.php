<?php

declare(strict_types=1);

namespace Forge\User\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Yii\Db\Migration\MigrationBuilder;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M211126112801Token
 */
final class M211126112801Token implements RevertibleMigrationInterface
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
            '{{%token}}',
            [
                'identity_id' => $b->integer()->notNull()->unsigned() . ' REFERENCES identity(id) ON DELETE CASCADE',
                'code' => $b->string(32)->notNull(),
                'created_at' => $b->integer()->notNull(),
                'type' => $b->smallInteger()->notNull(),
            ],
            $tableOptions
        );

        $b->createIndex('token_unique', '{{%token}}', ['identity_id', 'code', 'type'], 'UNIQUE');
    }

    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('{{%token}}');
    }
}
