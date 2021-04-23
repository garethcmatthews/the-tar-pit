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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AboutAction
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * Constructor
     *
     * @param RouterInterface $router
     * @param TemplateRendererInterface $template
     */
    public function __construct(RouterInterface $router, TemplateRendererInterface $template = null)
    {
        $this->router = $router;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $server = $request->getServerParams();
        if (!empty($server['HTTP_X_REQUESTED_WITH']) && strtolower($server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return new HtmlResponse($this->template->render('opcache::about-page'));
        }

        return new EmptyResponse();
    }
}
