<?php

declare(strict_types=1);

use Yiisoft\Translator\TranslatorInterface;

/**
 * @var string $moduleName
 * @var array $params
 * @var TranslatorInterface $translator
 *
 * @psalm-var string[] $params
 */
?>
<?= $translator->translate('mailer.new_password.header.text', ['username' => $params['username']]) ?>
<?= $translator->translate('mailer.new_password.body.text_1', ['moduleName' => $moduleName]) ?>
<?= $translator->translate('mailer.new_password.body.text_2') ?>

<strong><?= $params['password'] ?></strong>
