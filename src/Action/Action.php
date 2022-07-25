<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\Model\FormModel;
use Forge\Service\View;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\ValidatorInterface;

abstract class Action
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private ValidatorInterface $validator,
        private View $view
    ) {
        // Set default category translation.
        $this->translator = $translator->withCategory('user');
    }

    protected function redirect(string $url): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $this->urlGenerator->generate($url));
    }

    public function translator(): TranslatorInterface
    {
        return $this->translator;
    }

    protected function validate(FormModel $formModel): bool
    {
        return $this->validator->validate($formModel)->isValid();
    }

    protected function view(): View
    {
        return $this->view->withViewParameters(['translator' => $this->translator]);
    }
}
