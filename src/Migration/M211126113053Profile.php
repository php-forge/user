<?php

declare(strict_types=1);

namespace Forge\User\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Yii\Db\Migration\MigrationBuilder;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M211126113053Profile
 */
final class M211126113053Profile implements RevertibleMigrationInterface
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
            '{{%profile}}',
            [
                'identity_id' => $b->primaryKey()->notNull()->unsigned() . ' REFERENCES identity(id) ON DELETE CASCADE',
                'name' => $b->string(255)->defaultValue(''),
                'public_email' => $b->string(255)->defaultValue(''),
                'location' => $b->string(255)->defaultValue(''),
                'website' => $b->string(255)->defaultValue(''),
                'bio' => $b->text()->defaultValue(''),
                'timezone' => $b->string(40)->defaultValue(''),
            ],
            $tableOptions
        );
    }

    /**
     * @throws InvalidConfigException|NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('{{%profile}}');
    }
}
