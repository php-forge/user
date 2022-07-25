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

<p class = 'mail-reconfirmation'>
    <?= $translator->translate('mailer.header', ['username' => $params['username']]) ?>
</p>

<p class = 'mail-reconfirmation'>
    <?= $translator->translate('mailer.email.change', ['email' => $params['email'], 'moduleName' => $params['moduleName']]) ?>
    <?= $translator->translate('mailer.email.change.instruction') ?>
</p>

<p class = 'mail-reconfirmation'>
    <strong><?= Button::create()->content(Encode::content($params['url']))->link($params['url'])->render() ?></strong>
</p>

<p class = 'mail-reconfirmation'>
    <?= $translator->translate('mailer.footer') ?>
</p>
