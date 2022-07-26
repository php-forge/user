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

<?= $translator->translate('mailer.recovery.header', ['username' => $params['username']]) ?>

<?= $translator->translate('mailer.recovery', ['moduleName' => $params['moduleName']]) ?>
<?= $translator->translate('mailer.recovery.instruction') ?>

<strong><?= Button::create()->content(Encode::content($params['url']))->link($params['url'])->render() ?></strong></strong>

<?= $translator->translate('mailer.footer') ?>
