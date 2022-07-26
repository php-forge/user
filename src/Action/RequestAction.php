<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\Service\Mailer;
use Forge\User\ActiveRecord\Token;
use Forge\User\Form\RequestForm;
use Forge\User\Module\ModuleInterface;
use Forge\User\Repository\AccountRepository;
use Forge\User\Service\TokenToUrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Http\Method;
use Yiisoft\Session\Flash\Flash;

final class RequestAction extends Action
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
        TokenToUrlService $tokenToUrlService
    ): ResponseInterface {
        /** @psalm-var array<string, array<array-key, mixed>|string> $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        // Create the form.
        $requestForm = new RequestForm($accountRepository, $this->translator());

        if ($method === Method::POST && $requestForm->load($body) && $this->validate($requestForm)) {
            $name = $module->getName();

            $params = [
                'moduleName' => $name,
                'url' => $tokenToUrlService->run($requestForm->getUserId(), Token::TYPE_RECOVERY),
                'username' => $requestForm->getUsername(),
            ];

            if (
                $mailer
                    ->layout($module->mailer()->getLayoutRequest())
                    ->subject($module->mailer()->getSubjectRequest())
                    ->send($requestForm->getEmail(), $params)
            ) {
                $flash->add(
                    'forge.user',
                    [
                        'content' => $this->translator()->translate('request.sent'),
                        'type' => 'info',
                    ],
                );
            }

            return $this->redirect('login');
        }

        if ($module->isPasswordRecovery()) {
            return $this->view()
                ->withViewPath('@user/storage/view')
                ->render('request', ['body' => $body, 'formModel' => $requestForm, 'module' => $module]);
        }

        return $requestHandler->handle($serverRequest);
    }
}
