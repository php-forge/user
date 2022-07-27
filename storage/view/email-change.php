<?php

declare(strict_types=1);

use Forge\Form\Field;
use Forge\Form\Form;
use Forge\Form\Input\Submit;
use Forge\Form\Input\Text;
use Forge\Html\Helper\Encode;
use Forge\Model\Contract\FormModelContract;
use Forge\User\Module\ModuleMailerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\Translator;
use Yiisoft\View\WebView;

/**
 * @var Aliases $aliases
 * @var CsrfTokenInterface $csrfToken
 * @var FormModelContract $formModel
 * @var ModuleMailerInterface $module
 * @var Translator $translator
 * @var UrlGeneratorInterface $urlGenerator
 * @var WebView $this
 */

$this->setTitle($translator->translate('email.change.title'));

$field = Field::create($aliases->get('@bootstrap5/Field.php'));
?>

<div class="container py-5">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <h1 class="mb-3 text-center"><?= Encode::content($this->getTitle()) ?></h1>

            <?= Form::create($aliases->get('@user/config/widget/Form.php'))
                ->action($urlGenerator->generate('email-change'))
                ->csrf($csrfToken->getValue())
                ->id('form-email-change')
                ->begin()
            ?>

            <?= $field->widget(Text::create(construct: [$formModel, 'email'])->autofocus()->tabindex(1)) ?>
            <?= $field->widget(Text::create(construct: [$formModel, 'oldEmail'])->autofocus()->readonly()->tabindex(2)) ?>


            <?= Field::create()
                ->widget(
                    Submit::create()
                        ->class('btn btn-lg btn-primary w-100')
                        ->id('email-change-button')
                        ->name('email-change-button')
                        ->tabindex(2)
                        ->value($translator->translate('login.button.submit'))
                )
                ->containerClass('d-flex justify-content-center mt-5') ?>

            <?= Form::end() ?>

        </div>
    </div>
</div>
