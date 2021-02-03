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

namespace CPCCore\Router\Factory;

use CPCCore\Router\Route\Model\RouteModel;
use CPCCore\Router\Route\Facade\RouteFacade;
use \Zend\Mvc\Router\Http\Segment;

/**
 * Factory for the Router Service
 *
 * @package CPCCore
 * @subpackage Router
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class RouterFactory
{

    /**
     * Create a new Route Object
     *
     * @param string $name
     * @return RouteModel
     */
    public function createRouteModel($name)
    {
        $route = new RouteModel($name);
        return $route;
    }

    /**
     * Create a new Route Facade Object
     *
     * @param RouteModel $route
     * @return RouteFacade
     */
    public function createRouteFacade(RouteModel $route)
    {
        return new RouteFacade($route);
    }

    /**
     * Create a Zend MVC Segment Route
     *
     * @param string $route
     * @param mixed $controller
     * @param array $constraints
     * @return \Zend\Mvc\Router\Http\Segment
     */
    public function createSegmentRoute($route, $controller, array $constraints)
    {
        return Segment::factory(['route' => $route, 'constraints' => $constraints, 'defaults' => ['controller' => $controller]]);
    }

}
