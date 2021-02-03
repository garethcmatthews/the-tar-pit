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

namespace CPCCore\Router\Route\Facade;

use \CPCCore\Router\Route\Model\RouteModel;
use \CPCCore\Router\Route\Facade\RouteFacade;

/**
 * Route Facade
 *
 * Provides a Public Interface to the Route Object hiding
 * unrequired methods and providing a fluent interface.
 *
 * @package CPCCore
 * @subpackage Router
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class RouteFacade
{

    /**
     * Route Model
     * @var RouteModel
     */
    private $route;

    /**
     * Constructor
     *
     * @param RouteModel $route
     */
    public function __construct(RouteModel $route)
    {
        $this->route = $route;
    }

    /**
     * Set the Route Constraints
     *
     * @param array $constraints
     * @return RouteFacade
     */
    public function setConstraints(array $constraints)
    {
        $this->route->setConstraints($constraints);
        return $this;
    }

    /**
     * Set the Controller
     *
     * @param mixed $controller
     * @return RouteFacade
     */
    public function setController($controller)
    {
        $this->route->setController($controller);
        return $this;
    }

    /**
     * Set the Route Methods
     *
     * @param array $methods
     * @return RouteFacade
     */
    public function setMethods(array $methods)
    {
        $this->route->setMethods($methods);
        return $this;
    }

    /**
     * Set the Route
     *
     * @param string $route
     * @return RouteFacade
     */
    public function setRoute($route)
    {
        $this->route->setRoute($route);
        return $this;
    }

}
