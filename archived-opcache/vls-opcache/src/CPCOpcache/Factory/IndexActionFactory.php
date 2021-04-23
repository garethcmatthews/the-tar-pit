<?php

/**
 * CPC Opcache - Tool for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache
 * @license    BSD 3-Clause
 */
namespace CPC\Opcache\Factory;

use CPC\Opcache\Action\IndexAction;
use CPC\Service\Opcache\OpcacheServiceInterface;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class IndexActionFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $router = $container->get(RouterInterface::class);
        $template = $container->get(TemplateRendererInterface::class);
        $service = $container->get(OpcacheServiceInterface::class);
        $config = $container->get('config');

        return new IndexAction($service, $config, $router, $template);
    }
}
