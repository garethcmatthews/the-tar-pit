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

namespace CPCCore\Router\Route\Model;

use CPCCore\Router\Route\Exception\RouteModelException;

/**
 * Route
 *
 * Stores the Generated Route, its methods and allowed formats.
 * Route is only generated when 'isMatch' called
 *
 * @package CPCCore
 * @subpackage Router
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class RouteModel
{

    /**
     * Route Parameter Constraints
     * The Route Parameters and Values returned by the route match
     * @var array
     */
    private $constraints = [];

    /**
     * Controller
     * @var string|callable
     */
    private $controller = '';

    /**
     * HTTP Methods
     * An array containing the HTTP Methods for this route.
     * If is it empty the Route matches ALL allowed HTTP Methods
     * @var array
     */
    private $methods = [];

    /**
     * Route name
     * @var string [a-zA-Z-]
     */
    private $name = '';

    /**
     * Route
     * @var string
     */
    private $route = '';

    /**
     * Constructor
     *
     * Set the Route Name
     * Set route to initially match all allowed HTTP Methods
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->methods = $this->getAllowedHttpMethods();
    }

    /**
     * Return the Constraints
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Return the Controller
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get Route Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the Route
     *
     * Returns the route in a format suitable for the
     * Zend MVC Segment router.
     *
     * @return string
     */
    public function getRoute()
    {
        return str_replace('@', ':', $this->route);
    }

    /**
     * Route hasMethod
     *
     * Check that the route has a method that
     * matches the current request method
     *
     * @param string $method
     * @return boolean
     */
    public function hasMethod($method)
    {
        return in_array($method, $this->methods);
    }

    /**
     * Set the Route Parameter Constraints
     *
     * @param array $constraints
     * @return boolean
     * @throws RouteModelException
     */
    public function setConstraints(array $constraints)
    {
        foreach ($constraints as $key => $value) {
            if (@preg_match("/$value/", null) === false) {
                throw new RouteModelException(sprintf('Route "%s" - Invalid constraint "%s" => "%s"', $this->name, $key, $value));
            }
            $this->constraints[$key] = $value;
        }

        return true;
    }

    /**
     * Set the Controller
     *
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $errors = [];
        if (is_array($controller)) {
            if (!array_key_exists('class', $controller)) {
                $errors[] = sprintf('Route "%s" - Controller "class" parameter is Missing.', $this->name);
            } elseif (!is_string($controller['class'])) {
                $errors[] = sprintf('Route "%s" - Controller "class" parameter is not a string.', $this->name);
            }
            if (!array_key_exists('method', $controller)) {
                $errors[] = sprintf('Route "%s" - Controller "method" parameter is Missing.', $this->name);
            } elseif (!is_string($controller['method'])) {
                $errors[] = sprintf('Route "%s" - Controller "method" parameter is not a string.', $this->name);
            }
        }

        if (!empty($errors)) {
            throw new RouteModelException(implode("\n", $errors));
        }

        $this->controller = $controller;
        return true;
    }

    /**
     * Set the Route Methods
     *
     * @param array $methods
     * @return boolean
     * @throws RouteModelException
     */
    public function setMethods(array $methods)
    {
        $allowed = $this->getAllowedHttpMethods();
        foreach ($methods as $method) {
            if (!in_array($method, $allowed)) {
                throw new RouteModelException(sprintf('Route "%s" - Invalid Method "%s"', $this->name, $method));
            }
        }

        $this->methods = $methods;
        return true;
    }

    /**
     * Set the Route
     *
     * @param string $route
     * @return boolean
     * @throws RouteModelException
     */
    public function setRoute($route)
    {
        if (!is_string($route) || empty($route)) {
            throw new RouteModelException(sprintf('Route "%s" - Route is empty or not a string.', $this->name));
        }
        if (!preg_match('/^(\/{1}|(\/{1}@?([0-9a-zA-Z-]+))+\/?)$/', $route)) {
            throw new RouteModelException(sprintf('Route "%s" - Route definition is invalid.', $this->name));
        }

        $this->route = str_replace('@', ':', $route);
        return true;
    }

    /**
     * Get the allowed HTTP Methods
     *
     * @return array
     */
    private function getAllowedHttpMethods()
    {
        return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];
    }

    /**
     * Set the Route name
     *
     * @param string $name
     * @throws RouteModelException
     */
    private function setName($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new RouteModelException('Name is empty or not a string.');
        }
        if (!preg_match('/^[a-zA-Z0-9-]{4,32}$/', $name)) {
            throw new RouteModelException('Name must be a valid string [a-zA-Z0-9-] of 4-32 characters.');
        }

        $this->name = $name;
    }

}
