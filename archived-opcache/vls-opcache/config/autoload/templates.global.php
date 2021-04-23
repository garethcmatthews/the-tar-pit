<?php

return [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler' => Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,
            Zend\Expressive\Template\TemplateRendererInterface::class => Zend\Expressive\ZendView\ZendViewRendererFactory::class,
            Zend\View\HelperPluginManager::class => Zend\Expressive\ZendView\HelperPluginManagerFactory::class,
        ],
    ],
    'templates' => [
        'map' => [
            'error/error' => 'templates/error/error.phtml',
            'error/404' => 'templates/error/404.phtml',
        ],
        'paths' => [
            'opcache' => ['templates/opcache'],
            'layout' => ['templates/layout'],
            'error' => ['templates/error'],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'BooleanToStringHelper' => CPC\ZendFramework\View\Helper\BooleanToStringHelper::class,
            'FormatMemorySize' => CPC\ZendFramework\View\Helper\FormatMemorySizeHelper::class,
            'FormatPercentage' => CPC\ZendFramework\View\Helper\FormatPercentageHelper::class,
            'Layout' => Zend\View\Helper\Layout::class,
            'Partial' => Zend\View\Helper\Partial::class
        ]
    ],
];
