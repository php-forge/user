<?php

declare(strict_types=1);

return [
    'NavBar' => [
        'brandClass()' => ['mx-auto navbar-brand'],
        'brandImage()' => ['/image/yii3-logo.png'],
        'brandImageAttributes()' => [['object-fit' => 'cover', 'width' => '160px']],
        'brandImageClass()' => ['d-inline-block align-text-top me-5'],
        'buttonToggle()' => [true],
        'buttonToggleClass()' => ['navbar-toggler'],
        'buttonToggleId()' => ['layout-navbar'],
        'class()' => ['navbar navbar-expand-lg navbar-light bg-light'],
        'template()' => ['{containerMenu}{toggle}{brand}'],
    ],
];
