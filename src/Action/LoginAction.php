<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\Form\LoginForm;
use Forge\User\Module\ModuleInterface;
use Forge\User\Service\LoginService;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Method;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\User\Login\Cookie\CookieLogin;
use Yiisoft\User\Login\Cookie\CookieLoginIdentityInterface;

final class LoginAction extends Action
{
    /**
     * @throws JsonException
     */
    public function run(
        CookieLogin $cookieLogin,
        Flash $flash,
        LoginService $loginService,
        ModuleInterface $module,
        ServerRequestInterface $serverRequest
    ): ResponseInterface {
        /** @psalm-var array<string, array|string> */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        // Create the form.
        $loginForm = new LoginForm($loginService, $module, $this->translator());
        $ip = (string) $serverRequest->getServerParams()['REMOTE_ADDR'];
        $loginForm->ip($ip);

        if ($method === Method::POST && $loginForm->load($body) && $this->validate($loginForm)) {
            $identity = $loginService->getIdentity();
            $lastLogin = $loginForm->getLastLogout() > 0
                ? date('Y-m-d G:i:s', $loginForm->getLastLogout())
                : $this->translator()->translate('login.welcome');
            $content = $this->translator()->translate('login.sign.in', ['lastLogin' => $lastLogin]);
            $flash->add('forge.user', ['content' => $content, 'type' => 'success']);

            if ($identity instanceof CookieLoginIdentityInterface && $loginForm->getAttributeValue('rememberMe')) {
                return $cookieLogin->addCookie($identity, $this->redirect('home'));
            }

            return $this->redirect('home');
        }

        return $this->view()
            ->withViewPath('@user/storage/view')
            ->render('login', ['body' => $body, 'formModel' => $loginForm, 'module' => $module]);
    }
}
