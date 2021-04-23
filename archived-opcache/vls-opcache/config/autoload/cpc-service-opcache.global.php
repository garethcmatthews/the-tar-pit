<?php

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
