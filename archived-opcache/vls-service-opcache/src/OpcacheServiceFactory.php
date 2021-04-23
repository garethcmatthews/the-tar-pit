<?php

/**
 * Opcache Service - Service for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache-service
 * @license    BSD 3-Clause
 */
namespace CPC\Service\Opcache;

use CPC\Service\Opcache\OpcacheService;
use Interop\Container\ContainerInterface;

class OpcacheServiceFactory
{

    public function __invoke(ContainerInterface $container)
    {
        return new OpcacheService($container);
    }
}
