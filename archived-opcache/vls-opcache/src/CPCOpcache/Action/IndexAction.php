<?php

/**
 * CPC Opcache - Tool for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache
 * @license    BSD 3-Clause
 */
namespace CPC\Opcache\Action;

use CPC\Service\Opcache\OpcacheServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class IndexAction
{

    /**
     * @var \ArrayObject
     */
    private $config;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var OpcacheServiceInterface
     */
    private $service;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * Constructor
     *
     * @param OpcacheServiceInterface $service
     * @param \ArrayObject $config
     * @param RouterInterface $router
     * @param TemplateRendererInterface $template
     */
    public function __construct(OpcacheServiceInterface $service, \ArrayObject $config, RouterInterface $router, TemplateRendererInterface $template = null)
    {
        $this->config = $config;
        $this->router = $router;
        $this->service = $service;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];
        $data['config'] = (object) $this->config->application_settings;
        
        return new HtmlResponse($this->template->render('opcache::index-page', $data));
    }
}
