<?php

declare(strict_types=1);

use Forge\Html\Widgets\Components\Alert;
use Yiisoft\Session\Flash\Flash;

/** @var Flash $flash */
$flash = $flash->get('forge.user') ?? [];

/** @psalm-var string[] $flashMessage $ */
foreach ($flash as $key => $message) {
    echo Alert::create()
        ->content($message['content'] ?? '')
        ->dismissing(true)
        ->type($message['type'] ?? 'primary')
        ->render();
}
