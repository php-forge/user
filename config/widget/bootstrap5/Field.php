<?php

declare(strict_types=1);

return [
    'Field' => [
        'class()' => ['form-control'],
        'containerClass()' => ['form-floating flex-grow-1 mb-3'],
        'inputTemplate()' => ['{input}' . PHP_EOL . '{label}'],
        'errorClass()' => ['d-block invalid-feedback'],
    ],
];
