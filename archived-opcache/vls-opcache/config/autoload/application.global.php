<?php

use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'debug' => false,
    'config_cache_enabled' => false,
    'application_settings' => [
        'content_tab' => 'Scripts', // [Overview | Scripts | Information]
        'wide_screen' => false, // bool
        'auto_refresh' => true, // bool
        'auto_refresh_interval' => 600, // int seconds
        'opcache_reset_confirm' => true, // bool
        'show_scripts_details_column' => true, // bool
    ],
    'zend-expressive' => [
        'error_handler' => [
            'template_404' => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
    'dependencies' => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
        ],
    ],
];
