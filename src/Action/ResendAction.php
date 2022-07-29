<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\Service\Mailer;
use Forge\User\Form\ResendForm;
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

final class ResendAction extends Action
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
        /** @psalm-var array<string, array|string> $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        $resendForm = new ResendForm($accountRepository, $this->translator);

        if ($method === Method::POST && $resendForm->load($body) && $this->validate($resendForm)) {
            $name = $module->getName();
            $email = $resendForm->getEmail();

            $params = [
                'moduleName' => $name,
                'username' => $resendForm->getUsername(),
                'url' => $tokenToUrlService->run($resendForm->getUserId(), TokenToUrlService::TYPE_CONFIRMATION),
            ];

            if (
                $mailer
                    ->layout($module->mailer()->getLayoutResend())
                    ->subject($module->mailer()->getSubjectResend())
                    ->send($email, $params)
            ) {
                $flash->add(
                    'forge.user',
                    [
                        'content' => $this->translator->translate('resend.recovery.success', ['email' => $email]),
                        'type' => 'warning',
                    ],
                );
            }

            return $this->redirect('login');
        }

        if ($module->isConfirmEmail()) {
            return $this->viewRenderer
                ->withViewPath('@user/storage/view')
                ->render('resend', ['body' => $body, 'formModel' => $resendForm, 'translator' => $this->translator]);
        }

        return $requestHandler->handle($serverRequest);
    }
}
