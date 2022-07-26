<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\Form\ProfileForm;
use Forge\User\Repository\ProfileRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Http\Method;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;

final class ProfileAction extends Action
{
    /**
     * @throws InvalidArgumentException|StaleObjectException|Throwable
     */
    public function run(
        CurrentUser $currentUser,
        Flash $flash,
        ProfileRepository $profileRepository,
        ServerRequestInterface $serverRequest,
        TranslatorInterface $translator
    ): ResponseInterface {
        /** @psalm-var array<string, array|string> */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        // Set default category translation.
        $translator = $translator->withCategory('user');

        // Create the form.
        $profileForm = new ProfileForm($profileRepository, $translator);

        $id = $currentUser->getId();

        if ($id !== null) {
            $profileForm->loadData($id);
        }

        if (
            $method === Method::POST
            && null !== $id
            && $profileForm->load($body)
            && $this->validate($profileForm)
            && $profileForm->update($id)
        ) {
            $flash->add(
                'forge.user',
                ['content' => $translator->translate('profile.updated'), 'type' => 'success'],
            );
        }

        return $this->view()
            ->withViewPath('@user/storage/view')
            ->render('profile', ['body' => $body, 'formModel' => $profileForm]);
    }
}
