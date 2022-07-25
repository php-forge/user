<?php

declare(strict_types=1);

return [
    'Nav' => [
        'class()' => ['collapse navbar-collapse ml-auto'],
        'id()' => ['layout-navbar'],
        'dropdownDefinitions()' => [
            [
                'container()' => [false],
                'dividerClass()' => ['dropdown-divider'],
                'headerClass()' => ['dropdown-header'],
                'itemClass()' => ['dropdown-item'],
                'itemsContainerClass()' => ['dropdown-menu'],
                'toggleAttributes()' => [
                    [
                        'aria-expanded' => 'false',
                        'data-bs-toggle' => 'dropdown',
                        'role' => 'button',
                    ],
                ],
                'toggleClass()' => ['nav-link dropdown-toggle'],
                'toggleType()' => ['link'],
            ],
        ],
        'menuDefinitions()' => [
            [
                'class()' => ['navbar-nav ms-auto mb-2 mb-lg-0'],
                'dropdownContainerClass()' => ['nav-item dropdown'],
                'itemsContainerClass()' => ['nav-item'],
                'linkClass()' => ['nav-link'],
            ],
        ],
    ],
];
