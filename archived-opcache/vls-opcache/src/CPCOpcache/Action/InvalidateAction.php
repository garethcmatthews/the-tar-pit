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
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Stdlib\Parameters;

class InvalidateAction
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var OpcacheServiceInterface
     */
    private $service;

    /**
     * Constructor
     *
     * @param OpcacheServiceInterface $service
     * @param RouterInterface $router
     */
    public function __construct(OpcacheServiceInterface $service, RouterInterface $router)
    {
        $this->router = $router;
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($request->getMethod() === 'POST') {
            $body = new Parameters($request->getParsedBody());
            $filename = $body->get('filename', '');

            if ($filename) {
                $data = $this->invalidateFile($filename);
                return new JsonResponse($data);
            }
        }
    }

    private function invalidateFile($filename)
    {
        $data = ['status' => 'success', 'data' => ''];

        $result = $this->service->invalidateFile($filename);
        if (!$result) {
            $data['status'] = 'fail';
            $data['data']['error']['message'] = 'Failed to Invalidate File';
        }

        return $data;
    }
}
