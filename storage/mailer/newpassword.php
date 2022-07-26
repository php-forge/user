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
<p class = 'mail-new_password'>
    <?= $translator->translate('mailer.new_password.header.text', ['username' => $params['username']]) ?>
</p>

<p class = 'mail-new_password'>
    <?= $translator->translate('mailer.new_password.body.text_1', ['moduleName' => $moduleName]) ?>
    <strong><?= $params['password'] ?></strong>
</p>

<p class = 'mail-new_password'>
    <?= $translator->translate('mailer.new_password.body.text_2') ?>
</p>
