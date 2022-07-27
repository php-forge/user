<?php

declare(strict_types=1);

namespace Forge\User\Action;

use Forge\User\ActiveRecord\Account;
use Forge\User\ActiveRecord\Identity;
use Forge\User\Repository\AccountRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\User\CurrentUser;

final class LogoutAction extends Action
{
    /**
     * @throws Exception|InvalidArgumentException|StaleObjectException
     */
    public function run(CurrentUser $currentUser, AccountRepository $accountRepository): ResponseInterface
    {
        $account = null;
        $id = $currentUser->getId();
        $identity = $currentUser->getIdentity();

        if ($id !== null) {
            /** @var Account|null  */
            $account = $accountRepository->findById($id);
        }

        $account?->updateAttributes(['last_logout_at' => time()]);

        if ($identity instanceof Identity) {
            $identity->regenerateCookieLoginKey();
            $identity->save();
        }

        $currentUser->logout();

        return $this->redirect('home');
    }
}
