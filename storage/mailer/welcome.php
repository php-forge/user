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

<p class = 'mailer-welcome'>
    <?= $translator->translate('mailer.header', ['username' => $params['username']]) ?>
</p>

<p class = 'mailer-welcome'>
    <?= $translator->translate('mailer.welcome', ['moduleName' => $params['moduleName']]) ?>
</p>

<p class = 'mailer-welcome'>
    <?php if ($params['showPassword']) : ?>
        <?= $translator->translate('mailer.welcome.password') ?>
        <strong><?= $params['password'] ?></strong>
    <?php endif ?>
</p>

<?php if (isset($params['url'])) : ?>
    <p class = 'mailer-welcome'>
        <?= $translator->translate('mailer.welcome.instruction') ?>
    </p>
    <p class = 'mailer-welcome'>
    <strong><?= Button::create()->content(Encode::content($params['url']))->link($params['url'])->render() ?></strong>
    </p>
    <p class = 'mailer-welcome'>
        <?= $translator->translate('mailer.footer') ?>
    </p>
<?php endif ?>
