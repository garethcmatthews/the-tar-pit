<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\ZendRouter::class
        ],
        'factories' => [
            CPC\Opcache\AboutAction::class => CPC\Opcache\Factory\AboutActionFactory::class,
            CPC\Opcache\ClearCacheAction::class => CPC\Opcache\Factory\ClearCacheActionFactory::class,
            CPC\Opcache\ContentAction::class => CPC\Opcache\Factory\ContentActionFactory::class,
            CPC\Opcache\IndexAction::class => CPC\Opcache\Factory\IndexActionFactory::class,
            CPC\Opcache\InvalidateAction::class => CPC\Opcache\Factory\InvalidateActionFactory::class
        ],
    ],
    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => CPC\Opcache\IndexAction::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'about',
            'path' => '/about',
            'middleware' => CPC\Opcache\AboutAction::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'clear-cache',
            'path' => '/clear-cache',
            'middleware' => CPC\Opcache\ClearCacheAction::class,
            'allowed_methods' => ['POST']
        ],
        [
            'name' => 'content',
            'path' => '/content',
            'middleware' => CPC\Opcache\ContentAction::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'invalidate',
            'path' => '/invalidate',
            'middleware' => CPC\Opcache\InvalidateAction::class,
            'allowed_methods' => ['GET', 'POST']
        ],
    ],
];
