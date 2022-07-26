<?php

declare(strict_types=1);

use Yiisoft\Aliases\Aliases;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\MessageFormatterInterface;

return [
    // Configure application CategorySource
    'translation.user' => static function (Aliases $aliases, MessageFormatterInterface $messageFormatter) {
        $messageSource = new MessageSource($aliases->get('@user/storage/message'));

        return new CategorySource('user', $messageSource, $messageFormatter);
    },
];
