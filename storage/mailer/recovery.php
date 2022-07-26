<?php

declare(strict_types=1);

use Forge\Html\Helper\Encode;
use Forge\Html\Widgets\Components\Button;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @var array $params
 * @var TranslatorInterface $translator
 *
 * @psalm-var string[] $params
 */
?>

<p class = 'mail-recovery'>
    <?= $translator->translate('mailer.header', ['username' => $params['username']]) ?>
</p>

<p class = 'mail-recovery'>
    <?= $translator->translate('mailer.recovery', ['moduleName' => $params['moduleName']]) ?>
    <?= $translator->translate('mailer.recovery.instruction') ?>
</p>

<p class = 'mail-recovery'>
    <strong><?= Button::create()->content(Encode::content($params['url']))->link($params['url'])->render() ?></strong>
</p>

<p class = 'mail-recovery'>
    <?= $translator->translate('mailer.footer') ?>
</p>
