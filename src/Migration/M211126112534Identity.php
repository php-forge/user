<?php

declare(strict_types=1);

namespace Forge\User\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Yii\Db\Migration\MigrationBuilder;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M211126112534Identity
 */
final class M211126112534Identity implements RevertibleMigrationInterface
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
            '{{%identity}}',
            [
                'id' => $b->primaryKey()->notNull()->unsigned(),
                'auth_key' => $b->string(32)->defaultValue(''),
            ],
            $tableOptions
        );
    }

    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('{{%identity}}');
    }
}
