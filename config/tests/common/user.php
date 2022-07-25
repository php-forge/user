<?php

declare(strict_types=1);

use Forge\User\Module\ModuleInterface;
use Forge\User\Tests\App\Module\Module;
use Yiisoft\ActiveRecord\ActiveRecordFactory;

return [
    ModuleInterface::class => static fn (ActiveRecordFactory $activeRecordFactory) => $activeRecordFactory
        ->createQueryTo(Module::class)->findOne(1),
];
