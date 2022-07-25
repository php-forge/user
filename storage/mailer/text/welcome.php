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

<?= $translator->translate('mailer.header', ['username' => $params['username']]) ?>
<?= $translator->translate('mailer.welcome', ['moduleName' => $params['moduleName']]) ?>

<?php if ($params['showPassword']) : ?>
    <?= $translator->translate('mailer.welcome.password') ?>
    <strong><?= $params['password'] ?></strong>
<?php endif ?>

<?php if (isset($params['url'])) : ?>
    <?= $translator->translate('mailer.welcome.instruction') ?>

    <strong><?= Button::create()->content(Encode::content($params['url']))->link($params['url'])->render() ?></strong>

    <?= $translator->translate('mailer.footer') ?>
<?php endif ?>
