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

namespace CPCCore\Application;

use CPCCore\Application\Exception\ApplicationServiceException;
use CPCCore\Application\Exception\ApplicationControllerException;
use CPCCore\Application\Exception\ApplicationRegistryException;
use CPCCore\Application\Exception\ApplicationRouteException;
use CPCCore\Application\Factory\ApplicationFactory;
use CPCCore\Application\Model\CallbacksModel;
use CPCCore\Application\Model\RegistryModel;
use CPCCore\Router\RouterService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Application Service
 *
 * @package CPCCore
 * @subpackage Application
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class ApplicationService
{

    /**
     * After Routing Callbacks
     * @var CallbacksModel
     */
    private $afterRouting;

    /**
     * Before Routing Callbacks
     * @var CallbacksModel
     */
    private $beforeRouting;

    /**
     * Controller
     * The controller for the mathed route.
     * This is extracted from Route Match
     * @var mixed
     */
    private $controller;

    /**
     * Factory
     * @var ApplicationFactory
     */
    private $factory;

    /**
     * Parameters
     * The parameters for the mathed route.
     * This is extracted from Route Match
     * @var array
     */
    private $parameters = [];

    /**
     * Application Registry Object
     * @var RegistryModel
     */
    private $registry;

    /**
     * Zend Request Object
     * @var Request
     */
    private $request;

    /**
     * Zend Respone Object
     * @var Response
     */
    private $response;

    /**
     * Constructor
     *
     * @param ApplicationFactory $factory
     */
    public function __construct(ApplicationFactory $factory = null)
    {
        if (is_null($factory)) {
            $factory = new ApplicationFactory();
        }

        $this->request = $factory->createRequest();
        $this->response = $factory->createResponse();
        $this->router = $factory->createRouterService();
        $this->beforeRouting = $factory->createCallbacksModel();
        $this->afterRouting = $factory->createCallbacksModel();
        $this->registry = $factory->createRegistryModel();
        $this->factory = $factory;
    }

    /**
     * Route Name
     * The Route name of the Matched Route
     * @var string
     */
    private $routeName;

    /**
     * Router
     * @var RouterService
     */
    private $router;

    /**
     * Add an After Route Callback
     *
     * @param mixed $callback
     */
    public function addAfterRouteCallback($callback)
    {
        return $this->afterRouting->add($callback);
    }

    /**
     * Add a Before Route Callback
     *
     * @param mixed $callback
     */
    public function addBeforeRouteCallback($callback)
    {
        return $this->beforeRouting->add($callback);
    }

    /**
     * Get a Registry Item
     *
     * @param string $key
     * @return mixed
     */
    public function getRegistry($key)
    {
        return $this->registry->get($key);
    }

    /**
     * Get the Zend Request Object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the Zend Response Object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Add a route
     *
     * @param string $name
     * @return RouteFacade
     * @throws ApplicationServiceException
     */
    public function route($name)
    {
        if (!is_string($name)) {
            throw new ApplicationRouteException('Route name is not a string.');
        }

        return $this->router->addRoute($name);
    }

    /**
     * Run
     *
     * Try to Match Routes
     * If route is matched then call controller
     * If no Route is matched issue 404
     * If exception issue 500
     */
    public function run()
    {
        $this->beforeRouting->execute();

        try {
            $match = $this->router->match($this->request);
            if ($match) {
                $this->extractRouteMatchParameters($match);
                $this->validateController();
                $this->callController();
            } else {
                $this->exec404();
            }
        } catch (\Exception $ex) {
            $this->exec500($ex);
        }

        $this->afterRouting->execute();
        $this->getResponse()->send();
    }

    /**
     * Set the Base Path
     *
     * Set the basepath for a route
     * Useful for when your controllers are in sub folders
     *
     * @param string $basePath
     * @return boolean
     * @throws ApplicationServiceException
     */
    public function setBasePath($basePath)
    {
        if (!is_string($basePath) || empty($basePath)) {
            throw new ApplicationServiceException('Base path is empty or is not a string.');
        }

        $path = trim($basePath, '/');
        $this->request->setBasePath('/' . $path);
        return true;
    }

    /**
     * Set 404 Callback
     *
     * Set a custom error 404 handler
     *
     * @param callable $callback
     * @return boolean
     * @throws ApplicationServiceException
     */
    public function setError404($callback)
    {
        if (false === is_callable($callback)) {
            throw new ApplicationServiceException('Application error HTTP404 callback is not callable.');
        }

        $this->registry->set('error-404', $callback);
        return true;
    }

    /**
     * Set 500 Callback
     *
     * Set a custom error 500 handler
     *
     * @param callable $callback
     * @return boolean
     * @throws ApplicationServiceException
     */
    public function setError500($callback)
    {
        if (false === is_callable($callback)) {
            throw new ApplicationServiceException('Application error HTTP500 callback is not callable.');
        }

        $this->registry->set('error-500', $callback);
        return true;
    }

    /**
     * Set a Registry Item
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setRegistry($key, $value)
    {
        if (preg_match("/^error-(.*)$/i", $key) > 0) {
            throw new ApplicationRegistryException(sprintf('Registry key invalid "%s". Keys starting with the string "error-" are reserved.', $key));
        }

        return $this->registry->set($key, $value);
    }

    /**
     * Call Controller Function
     *
     * @return boolean
     * @throws ApplicationServiceException
     */
    private function callController()
    {
        if (is_array($this->controller)) {
            $class = $this->controller['class'];
            $method = $this->controller['method'];

            $controller = new $class;
            if (false === is_callable(array($controller, $method))) {
                throw new ApplicationControllerException(sprintf('Route "%s" error calling controller method "%s"', $this->routeName, $method));
            }
            $controller->$method();
        } else {
            if (false === call_user_func_array($this->controller, $this->parameters)) {
                throw new ApplicationControllerException(sprintf('Route "%s" error calling controller function', $this->routeName));
            }
        }

        return true;
    }

    /**
     * Error 404
     *
     * Set the response code to 404
     * If a callback has been set then call it and pass the response object
     * back for the user to configure the response manually.
     */
    private function exec404()
    {
        $this->getResponse()->setStatusCode(404);
        $this->getResponse()->setContent("404 Error Page Not Found");
        $error404 = $this->registry->get('error-404');

        if ($error404) {
            call_user_func_array($error404, [$this->getResponse()]);
        }
    }

    /**
     * Error 500
     *
     * Set the response code to 500
     * If a callback has been set then call it and pass the response object.
     *
     * @param \Exception $ex
     */
    private function exec500(\Exception $ex)
    {
        $this->getResponse()->setStatusCode(500);
        $error500 = $this->registry->get('error-500');
        if ($error500) {
            call_user_func_array($error500, [$this->getResponse(), $ex]);
        } else {
            $this->getResponse()->setContent(nl2br($ex->getMessage()));
        }
    }

    /**
     * Extract the Route Match Parameters
     *
     * Extracts
     *  --Routename
     *  --Controller
     *  --Route Parameters
     *
     * @param \Zend\Mvc\Router\Http\RouteMatch $match
     * @return type
     */
    private function extractRouteMatchParameters(\Zend\Mvc\Router\Http\RouteMatch $match)
    {
        $this->routeName = $match->getMatchedRouteName();

        $params = $match->getParams();
        $this->controller = $params['controller'];
        unset($params['controller']);

        $this->parameters = $params;
    }

    /**
     * Validate the Controller
     *
     * @throws ApplicationServiceException
     */
    private function validateController()
    {
        $controller = $this->controller;
        if (is_array($controller)) {
            $class = $controller['class'];
            $method = $controller['method'];

            if (!class_exists($class)) {
                throw new ApplicationControllerException(sprintf('Route "%s" - Controller Class "%s" not found.', $this->routeName, $class));
            }
            if (!method_exists($class, $method)) {
                throw new ApplicationControllerException(sprintf('Route "%s" - Controller Method "%s" does not exist.', $this->routeName, $method));
            }
        } else {
            if (!is_callable($controller)) {
                throw new ApplicationControllerException(sprintf('Route "%s" - Controller function cannot be found or is not callable.', $this->routeName));
            }
        }
    }

}
