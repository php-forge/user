<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\Service\Mailer;
use Forge\User\ActiveRecord\Token;
use Forge\User\Form\RegisterForm;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Service\TokenToUrlService;
use Forge\User\Service\UserRegisterService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Http\Method;
use Yiisoft\Session\Flash\Flash;

final class RegisterAction extends Action
{
    /**
     * @throws Exception|InvalidConfigException|NotSupportedException|Throwable
     */
    public function run(
        AccountRepository $accountRepository,
        Flash $flash,
        Mailer $mailer,
        ModuleInterface $module,
        RequestHandlerInterface $requestHandler,
        ServerRequestInterface $serverRequest,
        TokenToUrlService $tokenToUrlService,
        UserRegisterService $userRegisterService
    ): ResponseInterface {
        /** @psalm-var array<string, array|string> $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();
        $ip = (string)$serverRequest->getServerParams()['REMOTE_ADDR'];

        // Create the form.
        $registerForm = new RegisterForm($accountRepository, $module, $this->translator);

        $registerForm->ip($ip);

        if (
            $method === Method::POST
            && $registerForm->load($body)
            && $this->validate($registerForm)
            && $userRegisterService->run(
                $registerForm,
                $module->isConfirmEmail(),
                $module->isGeneratePassword()
            )
        ) {
            $email = $registerForm->getEmail();
            $name = $module->getName();

            $params = [
                'moduleName' => $name,
                'password' => $registerForm->getPassword(),
                'showPassword' => $module->isGeneratePassword(),
                'url' => $module->isConfirmEmail()
                    ? $tokenToUrlService->run($registerForm->getUserId(), Token::TYPE_CONFIRMATION)
                    : null,
                'username' => $registerForm->getUsername(),
            ];

            if (
                $mailer
                    ->layout($module->mailer()->getLayoutRegister())
                    ->subject($module->mailer()->getSubjectRegister())
                    ->send($email, $params)
            ) {
                $message = $module->isConfirmEmail()
                    ? $this->translator->translate('register.unconfirmed')
                    : $this->translator->translate('register.confirmed');
                $flash->add('forge.user', ['content' => $message, 'type' => 'info']);
            }

            $redirect = !$module->isConfirmEmail() && !$module->isGeneratePassword()
                ? 'login'
                : 'home';

            return $this->redirect($redirect);
        }

        if ($module->isRegister()) {
            return $this->viewRenderer
                ->withViewPath('@user/storage/view')
                ->render(
                    'register',
                    ['body' => $body, 'formModel' => $registerForm, 'module' => $module, 'translator' => $this->translator],
                );
        }

        return $requestHandler->handle($serverRequest);
    }
}
