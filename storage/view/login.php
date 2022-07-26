<?php

declare(strict_types=1);

use Forge\Form\Contract\FormModelContract;
use Forge\Form\Field;
use Forge\Form\Form;
use Forge\Form\Input\Checkbox;
use Forge\Form\Input\Password;
use Forge\Form\Input\Submit;
use Forge\Form\Input\Text;
use Forge\Html\Helper\Encode;
use Forge\Html\Tag\Tag;
use Forge\Html\Widgets\Components\Button;
use Yii\Extension\User\Settings\ModuleSettings;
use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\Translator;
use Yiisoft\View\WebView;

/**
 * @var CsrfTokenInterface $csrf
 * @var FormModelContract $formModelModel
 * @var ModuleSettings $module
 * @var Translator $translator
 * @var UrlGeneratorInterface $urlGenerator
 * @var WebView $this
 */

$this->setTitle($translator->translate('login.title'));

$field = Field::create($aliases->get('@bootstrap5/Field.php'));
$items = [];

if ($module->isPasswordRecovery() && $module->isConfirmEmail()) {
    $marginButtonRegister = 'margin-top:7rem';
} elseif ($module->isPasswordRecovery() && !$module->isConfirmEmail()) {
    $marginButtonRegister = 'margin-top:13rem;';
} elseif (!$module->isPasswordRecovery() && $module->isConfirmEmail()) {
    $marginButtonRegister = 'margin-top:9rem;';
} else {
    $marginButtonRegister = 'margin-top:15rem;';
}

?>

<div class="container py-5">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <h1 class="mb-3 text-center"><?= Encode::content($this->getTitle()) ?></h1>

            <?= Form::create($aliases->get('@user/config/widget/Form.php'))
                ->action($urlGenerator->generate('login'))
                ->csrf($csrfToken->getValue())
                ->id('form-auth-login')
                ->begin()
            ?>

                <?= $field->widget(Text::create(construct: [$formModel, 'login'])->autofocus()->tabindex(1)) ?>

                <?= $field->widget(Password::create(construct: [$formModel, 'password'])->tabindex(2)) ?>

                <?php if ($module->isRememberMe()) : ?>
                    <?= Field::create($aliases->get('@bootstrap5/Checkbox.php'))
                        ->class('form-check-input')
                        ->containerClass('form-check form-switch')
                        ->labelClass('form-check-label')
                        ->widget(Checkbox::create(construct: [$formModel, 'rememberMe'])->tabindex(3)) ?>
                <?php endif ?>

                <?= Field::create()
                    ->widget(
                        Submit::create()
                            ->class('btn btn-lg btn-primary w-100')
                            ->id('login-button')
                            ->name('login-button')
                            ->tabindex(4)
                            ->value($translator->translate('login.button.submit'))
                    )
                    ->containerClass('d-flex justify-content-center mt-5') ?>

                <?php if ($module->isPasswordRecovery()) : ?>
                    <?php $items[] = Tag::li(
                        ['class' => 'border-0 list-group-item text-center'],
                        Tag::a(
                            ['href' => $urlGenerator->generate('request'), 'tabindex' => 5],
                            $translator->translate('login.recovery.password.link'),
                        )
                    ) ?>
                <?php endif ?>

                <?php if ($module->isConfirmEmail()) : ?>
                    <?php $items[] = Tag::li(
                        ['class' => 'border-0 list-group-item text-center mb-5'],
                        Tag::a(
                            ['href' => $urlGenerator->generate('resend'), 'tabindex' => 5],
                            $translator->translate('login.confirmation.resend.link'),
                        )
                    ) ?>
                <?php endif ?>

                <?= Tag::ul(['class' => 'list-group list-group-flush mt-3'], $items) ?>

                <?php if ($module->isRegister()) : ?>
                    <div class="d-sm-none" style=<?= $marginButtonRegister ?>>
                        <?= Button::create($aliases->get('@bootstrap5/Button.php'))
                            ->content($translator->translate('register.link'))
                            ->link($urlGenerator->generate('register'))
                            ->render() ?>
                    </div>
                <?php endif ?>

            <?= Form::end() ?>

        <div>
    <div>
<div>
