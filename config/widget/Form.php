<?php

declare(strict_types=1);

use Yiisoft\Http\Method;

$novalidate = getenv('YII_ENV') === 'tests' || YII_ENV === 'tests' ? ['novalidate' => true] : [];

return [
    'Form' => [
        'attributes()' => [$novalidate],
        'enctype()' => ['multipart/form-data'],
        'method()' => [Method::POST],
    ],
];
