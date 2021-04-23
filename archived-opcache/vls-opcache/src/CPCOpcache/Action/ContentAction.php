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
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ContentAction
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
        $server = $request->getServerParams();
        if (!empty($server['HTTP_X_REQUESTED_WITH']) && strtolower($server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if (ini_get("opcache.enable") != 1) {
                return new HtmlResponse($this->template->render('opcache::no-opcache-page'));
            }

            $data['scripts'] = $this->service->getCachedScripts();
            $data['functions'] = $this->service->getFunctions();
            $data['version'] = $this->service->getVersionDetails();
            $data['memoryUsage'] = $this->service->getMemoryUsage();
            $data['status'] = $this->service->getStatus();
            $data['statistics'] = $this->service->getStatistics();
            $data['directives'] = $this->service->getDirectives();
            //$data['blacklist'] = $this->service->getBlacklist();

            return new HtmlResponse($this->template->render('opcache::content-page', $data));
        }

        return new EmptyResponse();
    }
}
