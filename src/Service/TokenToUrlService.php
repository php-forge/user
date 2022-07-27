<?php

declare(strict_types=1);

namespace Forge\User\Service;

use Forge\User\ActiveRecord\Token;
use Forge\User\Repository\TokenRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;
use Throwable;
use Yiisoft\ActiveRecord\ActiveRecordFactory;
use Yiisoft\Db\Connection\Connection;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\Random;

final class TokenToUrlService
{
    public const TYPE_CONFIRMATION = 0;
    public const TYPE_RECOVERY = 1;
    public const TYPE_CONFIRM_NEW_EMAIL = 2;
    public const TYPE_CONFIRM_OLD_EMAIL = 3;
    /** @psalm-var string[] */
    private array $routes = [
        self::TYPE_CONFIRMATION => 'confirm',
        self::TYPE_RECOVERY => 'reset',
        self::TYPE_CONFIRM_NEW_EMAIL => 'email/attempt',
        self::TYPE_CONFIRM_OLD_EMAIL => 'email/attempt',
    ];

    public function __construct(
        private ActiveRecordFactory $activeRecordFactory,
        private ConnectionInterface $db,
        private LoggerInterface $logger,
        private TokenRepository $tokenRepository,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @throws Exception|InvalidConfigException|NotSupportedException|Throwable
     */
    public function run(string $id, int $type): string
    {
        $urlAbsolute = '';

        if ($this->register($id, $type)) {
            /** @var Token $token */
            $token = $this->tokenRepository->findByOneCondition(['identity_id' => $id, 'type' => $type]);

            $urlAbsolute = $this->urlGenerator->generateAbsolute(
                $this->routes[$token->getType()],
                ['id' => $token->getUserId(), 'code' => $token->getCode()]
            );
        }

        return $urlAbsolute;
    }

    /**
     * @throws Exception|InvalidConfigException|NotSupportedException|Throwable
     */
    private function register(string $id, int $type): bool
    {
        /** @var Token $token */
        $token = $this->activeRecordFactory->createAR(Token::class);

        $token->deleteAll(['identity_id' => $id, 'type' => $type]);

        if ($token->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        /** @var Connection $db */
        $db = $this->db;
        $transaction = $db->beginTransaction();

        try {
            $token->setAttribute('identity_id', (int) $id);
            $token->setAttribute('type', $type);
            $token->setAttribute('created_at', time());
            $token->setAttribute('code', Random::string());

            if (!$token->save()) {
                $transaction->rollBack();

                return false;
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }

        return true;
    }
}
