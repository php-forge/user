<?php

declare(strict_types=1);

use Yiisoft\Http\Method;

$novalidate = YII_ENV === 'tests' ? ['novalidate' => true] : [];

return [
    'Form' => [
        'attributes()' => [$novalidate],
        'enctype()' => ['multipart/form-data'],
        'method()' => [Method::POST],
    ],
];
