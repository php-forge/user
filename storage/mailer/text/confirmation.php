<?php

declare(strict_types=1);

use Yiisoft\Translator\TranslatorInterface;

/**
 * @var array $params
 * @var TranslatorInterface $translator
 *
 * @psalm-var string[] $params
 */
?>

<?= $translator->translate('mailer.header', ['username' => $params['username']]) ?>

<?= $translator->translate('mailer.resend', [$params['moduleName']]) ?>
<?= $translator->translate('mailer.resend.instruction') ?>

<strong><?= $params['url'] ?></strong>

<?= $translator->translate('mailer.footer') ?>
