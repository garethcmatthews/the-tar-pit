# cpc-service-opcache
Service Layer for PHP Opcache

[![Build Status](https://travis-ci.org/VirtualLighStudios/cpc-service-opcache.svg?branch=master)](https://travis-ci.org/VirtualLighStudios/cpc-service-opcache)

About
------------

cpc-service-opcache is a wrapper for PHP's Opcache functionality.

Installation
----------------------------

1. In your projects `composer.json` file add `"crossplatformcoder/cpc-service-opcache":"^1.0.0"` into the `require` section.
2. Run `composer install` or `composer update`.

Methods
-----

Refer to the Interface file for available functions CPC\Service\Opcache\OpcacheServiceInterface

Usage
-----

The service requires a locator to load its dependencies. [Zend Service Manager](https://docs.zendframework.com/zend-servicemanager/quick-start/) is suitable.

    return [
        'dependencies' => [
            'invokables' => [
                CPC\Service\Opcache\Model\ConfigurationModel::class => CPC\Service\Opcache\Model\ConfigurationModel::class,
                CPC\Service\Opcache\Model\InformationModel::class => CPC\Service\Opcache\Model\InformationModel::class,
                CPC\Service\Opcache\Model\StatusModel::class => CPC\Service\Opcache\Model\StatusModel::class
            ],
            'factories' => [
                CPC\Service\Opcache\OpcacheServiceInterface::class => CPC\Service\Opcache\OpcacheServiceFactory::class
            ],
        ],
    ];

Zend Expressive
-----

If you are using it with Zend Expressive add the service to your composer file 

    "require": {
        "crossplatformcoder/cpc-service-opcache": "^1.0",
    },

then copy the config file (config/autoload/cpc-service-opcache.global) into the Expressive projects config-autoload folder.



