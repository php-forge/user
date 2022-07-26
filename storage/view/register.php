<?php

declare(strict_types=1);

use Forge\Form\Field;
use Forge\Form\Form;
use Forge\Form\Input\Password;
use Forge\Form\Input\Submit;
use Forge\Form\Input\Text;
use Forge\Html\Helper\Encode;
use Forge\Html\Tag\Tag;
use Forge\Html\Widgets\Components\Button;
use Yii\Extension\User\Settings\ModuleSettings;
use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Form\FormModelInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\Translator;
use Yiisoft\View\WebView;

 /**
  * @var CsrfTokenInterface $csrfToken
  * @var FormModelInterface $model
  * @var ModuleSettings $module
  * @var Translator $translator
  * @var UrlGeneratorInterface $urlGenerator
  * @var WebView $this
  */

$this->setTitle($translator->translate('register.title'));
$field = Field::create($aliases->get('@bootstrap5/Field.php'));
$marginLoginButton = $module->isGeneratePassword() === false ? 'margin-top:10rem;' : 'margin-top:24rem;';
?>

<div class="container py-5">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <h1 class="mb-3 text-center"><?= Encode::content($this->getTitle()) ?></h1>

            <?= Form::create($aliases->get('@user/config/widget/Form.php'))
                ->action($urlGenerator->generate('register'))
                ->csrf($csrfToken->getValue())
                ->id('form-registration-register')
                ->begin() ?>

                <?= $field->widget(Text::create(construct: [$formModel, 'email'])->autofocus()->tabindex(1)) ?>

                <?= $field->widget(Text::create(construct: [$formModel, 'username'])->autofocus()->tabindex(2)) ?>

                <?php if ($module->isGeneratePassword() === false) : ?>
                    <?= $field->widget(Password::create(construct: [$formModel, 'password'])->tabindex(3)) ?>
                    <?= $field->widget(Password::create(construct: [$formModel, 'passwordVerify'])->tabindex(4)) ?>
                <?php endif ?>

                <?= Field::create()
                    ->widget(
                        Submit::create()
                            ->class('btn btn-lg btn-primary w-100')
                            ->id('register-button')
                            ->name('register-button')
                            ->tabindex(5)
                            ->value($translator->translate('register.button.submit'))
                    )
                    ->containerClass('d-flex justify-content-center mt-5') ?>

                <?= Tag::div(
                    ['class' => 'd-sm-none','style' => $marginLoginButton],
                    Button::create($aliases->get('@bootstrap5/Button.php'))
                        ->content($translator->translate('login.link'))
                        ->link($urlGenerator->generate('login'))
                        ->render(),
                ) ?>

            <?= Form::end() ?>

        </div>
    </div>
</div>
