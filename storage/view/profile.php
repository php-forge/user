<?php

declare(strict_types=1);

use Forge\Form\Field;
use Forge\Form\Form;
use Forge\Form\Input\Email;
use Forge\Form\Input\Submit;
use Forge\Form\Input\Text;
use Forge\Form\Select;
use Forge\Form\TextArea;
use Forge\Html\Helper\Encode;
use Forge\Model\Contract\FormModelContract;
use Forge\User\Module\ModuleInterface;
use Yii\Extension\Helpers\TimeZone;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\Translator;
use Yiisoft\View\WebView;

 /**
  * @var Aliases $aliases
  * @var CsrfTokenInterface $csrfToken
  * @var FormModelContract $formModel
  * @var ModuleInterface $module
  * @var Translator $translator
  * @var UrlGeneratorInterface $urlGenerator
  * @var WebView $this
  */

$this->setTitle($translator->translate('profile.title'));

$field = Field::create($aliases->get('@bootstrap5/Field.php'));
/** @var iterable[][] $timeZones */
$timeZones = (new TimeZone())->getAll();
$timeZone = ArrayHelper::map($timeZones, 'timezone', 'name');
?>

  <div class="container py-5">
      <div class="d-flex justify-content-center">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <h1 class="mb-3 text-center"><?= Encode::content($this->getTitle()) ?></h1>

                <?= Form::create($aliases->get('@user/config/widget/Form.php'))
                    ->action($urlGenerator->generate('profile'))
                    ->csrf($csrfToken->getValue())
                    ->id('form-profile')
                    ->begin() ?>

                    <?= $field->widget(Text::create(construct: [$formModel, 'name'])->autofocus()->tabindex(1)) ?>
                    <?= $field->widget(Email::create(construct: [$formModel, 'publicEmail'])->tabindex(2)) ?>
                    <?= $field->widget(Text::create(construct: [$formModel, 'website'])->tabindex(3)) ?>
                    <?= $field->widget(Text::create(construct: [$formModel, 'location'])->tabindex(4)) ?>
                    <?= $field
                        ->widget(Select::create(construct: [$formModel, 'timezone'])->items($timeZone)->tabindex(5)) ?>
                    <?= $field->widget(TextArea::create(construct: [$formModel, 'bio'])->rows(2)->tabindex(6)) ?>
                    <?= Field::create()
                        ->widget(
                            Submit::create()
                                ->class('btn btn-lg btn-primary w-100')
                                ->id('save-profile')
                                ->name('save-profile')
                                ->tabindex(7)
                                ->value($translator->translate('profile.button.submit'))
                        )
                        ->containerClass('d-flex justify-content-center mt-5') ?>

                <?= Form::end() ?>

        </div>
    </div>
</div>
