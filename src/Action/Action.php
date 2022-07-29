<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\Model\FormModel;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Yii\View\ViewRenderer;

abstract class Action
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        protected TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private ValidatorInterface $validator,
        protected ViewRenderer $viewRenderer
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

    protected function validate(FormModel $formModel): bool
    {
        return $this->validator->validate($formModel)->isValid();
    }
}
