<?php

declare(strict_types=1);

namespace Forge\User\Tests\Tests\Unit;

use Forge\User\Tests\Support\UnitTester;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Files\FileHelper;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Db\Migration\Service\MigrationService;
use Yiisoft\Yii\Runner\ConfigFactory;

final class MigrationUnitCest
{
    private ContainerInterface $container;
    private array $params;

    public function _before(UnitTester $I): void
    {
        $config = $this->getConfig();

        $this->params = $config->get('params');

        $containerConfig = ContainerConfig::create()->withDefinitions($config->get('console'));
        $this->container = new Container($containerConfig);
    }

    public function testMigrationUp(UnitTester $I): void
    {
        $file = dirname(__DIR__) . '/_output/yiitest.sq3';

        $migration = $this->container->get(MigrationService::class);

        $migration->updateNamespaces([
            'Forge\\User\\Migration',
            'Forge\\User\\Tests\\App\\Migration',
        ]);

        if (file_exists($file)) {
            FileHelper::unlink($file);
        }

        $application = $this->container->get(Application::class);

        $loader = new ContainerCommandLoader(
            $this->container,
            $this->params['yiisoft/yii-console']['commands']
        );

        $application->setCommandLoader($loader);

        $command = new CommandTester($application->find('migrate/up'));

        $command->setInputs(['yes']);

        $I->assertEquals(ExitCode::OK, $command->execute([]));
    }

    public function testMigrationDown(UnitTester $I): void
    {
        $migration = $this->container->get(MigrationService::class);

        $migration->updateNamespaces([
            'Forge\\User\\Migration',
            'Forge\\User\\Tests\\App\\Migration',
        ]);

        $application = $this->container->get(Application::class);

        $loader = new ContainerCommandLoader(
            $this->container,
            $this->params['yiisoft/yii-console']['commands']
        );

        $application->setCommandLoader($loader);

        $command = new CommandTester($application->find('migrate/down'));

        $command->setInputs(['yes']);

        $I->assertEquals(ExitCode::OK, $command->execute([]));
    }

    public function testMigration(UnitTester $I): void
    {
        $migration = $this->container->get(MigrationService::class);

        $migration->updateNamespaces([
            'Forge\\User\\Migration',
            'Forge\\User\\Tests\\App\\Migration',
        ]);

        $application = $this->container->get(Application::class);

        $loader = new ContainerCommandLoader(
            $this->container,
            $this->params['yiisoft/yii-console']['commands']
        );

        $application->setCommandLoader($loader);

        $command = new CommandTester($application->find('migrate/up'));

        $command->setInputs(['yes']);

        $I->assertEquals(ExitCode::OK, $command->execute([]));
    }

    private function getConfig(): Config
    {
        return ConfigFactory::create(new ConfigPaths(dirname(__DIR__, 2), 'config'), 'tests');
    }
}
