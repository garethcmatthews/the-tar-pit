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

namespace CPCCore\Router;

use CPCCore\Router\Exception\RouterServiceException;
use CPCCore\Router\Factory\RouterFactory;
use CPCCore\Router\Route\Facade\RouteFacade;
use CPCCore\Router\Route\Model\RouteModel;
use \Zend\Http\PhpEnvironment\Request;

/**
 * Router
 *
 * Main entry for the Router Module
 *
 * @package CPCCore
 * @subpackage Router
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class RouterService
{

    /**
     * Factory
     * @var RouterFactory
     */
    private $factory;

    /**
     * Request Object
     * @var Request
     */
    private $request;

    /**
     * Collection of routes
     * @var RouteModel[]
     */
    private $routes = [];

    /**
     * Constructor
     *
     * Create a Router Module Factory
     * Store the Request Object
     *
     * @param Request $request
     * @param RouterFactory $factory
     */
    public function __construct(Request $request, RouterFactory $factory = null)
    {
        $this->request = $request;

        if (is_null($factory)) {
            $factory = new RouterFactory();
        }
        $this->factory = $factory;
    }

    /**
     * Add a new Route
     *
     * Generate a route add it to the router and return a
     * Route Service object as a public interface to the route object
     *
     * @param string $name
     * @return RouteFacade
     * @throws RouterServiceException
     */
    public function addRoute($name)
    {
        $route = $this->factory->createRouteModel($name);

        if (array_key_exists($name, $this->routes)) {
            throw new RouterServiceException(sprintf('Route name "%s" is already in use', $name));
        }

        $facade = $this->factory->createRouteFacade($route);
        $this->routes[$name] = $route;

        return $facade;
    }

    /**
     * Match Routes
     *
     * Go through the routes in the order that they were defined.
     * If the route has the method that match the current HTTP request
     * generate the Segment route and check for a match.
     *
     * @return \Zend\Mvc\Router\Http\RouteMatch|false
     */
    public function match()
    {
        /* @var $routeModel RouteModel */
        foreach ($this->routes as $routeModel) {

            if (!$routeModel->hasMethod($this->request->getMethod())) {
                continue;
            }

            $route = $routeModel->getRoute();
            if (!empty($this->request->getBasePath())) {
                $route = $this->request->getBasePath() . $route;
            }

            $segmentRouter = $this->factory->createSegmentRoute($route, $routeModel->getController(), $routeModel->getConstraints());
            $match = $segmentRouter->match($this->request);
            if ($match) {
                $match->setMatchedRouteName($routeModel->getName());
                return $match;
            }
        }
        return false;
    }

}
