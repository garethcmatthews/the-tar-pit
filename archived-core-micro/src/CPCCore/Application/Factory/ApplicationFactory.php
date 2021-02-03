<?php

/**
 * CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
 *
 * @author      Gareth C Matthews <garethmatthews911@gmail.com>
 * @copyright   2015 Gareth Matthews
 * @link        https://github.com/CrossPlatformCoder/CPCCore
 * @license     BSD 3-Clause
 * @version     1.0.0
 */

namespace CPCCore\Application\Factory;

use CPCCore\Application\Model\CallbacksModel;
use CPCCore\Application\Model\RegistryModel;
use CPCCore\Router\RouterService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Factory for the Application Service
 *
 * @package CPCCore
 * @subpackage Application
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class ApplicationFactory
{

    /**
     * Request Object
     * @var Request;
     */
    private $request;

    /**
     * Create a Request Object
     *
     * @return Request
     */
    public function createRequest()
    {
        $request = new Request();
        $this->request = $request;
        return $request;
    }

    /**
     * Create a Response Object
     *
     * @return Response
     */
    public function createResponse()
    {
        $response = new Response();
        return $response;
    }

    /**
     * Create a Router Service
     *
     * @return RouterService
     */
    public function createRouterService()
    {
        $service = new RouterService($this->getRequest());
        return $service;
    }

    /**
     * Create a Callbacks Model
     *
     * @return CallbacksModel
     */
    public function createCallbacksModel()
    {
        $model = new CallbacksModel();
        return $model;
    }

    /**
     * Create a Registry Model
     *
     * @return RegistryModel
     */
    public function createRegistryModel()
    {
        $model = new RegistryModel();
        return $model;
    }

    /**
     * Get the Request Object
     *
     * @return Request
     */
    private function getRequest()
    {
        if (is_null($this->request)) {
            $this->createRequest();
        }
        return $this->request;
    }

}
