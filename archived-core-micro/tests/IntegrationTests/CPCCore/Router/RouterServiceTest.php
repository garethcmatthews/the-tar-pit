<?php

namespace CPCCoreIntegrationTest\Router\Route;

use \CPCCore\Router\RouterService;
use \Zend\Http\PhpEnvironment\Request;

class RouterServiceTest extends \PHPUnit_Framework_TestCase
{

    const RANDOMISED_TEST_LOOPS = 25;

    public function testAddRoutes_ReturnsRouteFacade()
    {
        $router = $this->getRouterService();
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $router->addRoute($this->generateRandomName()));
        }
    }

    public function testAddRoutes_DuplicateRouteName_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Exception\RouterServiceException', sprintf('Route name "%s" is already in use', $name));
        $router = $this->getRouterService();
        $router->addRoute($name);
        $router->addRoute($name);
    }

    public function testMatch()
    {
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {

            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $router = $this->getRouterService();
            $route = $router->addRoute($this->generateRandomName());
            $route->setRoute($uri);

            $match = $router->match();
            $this->assertInstanceOf('\Zend\Mvc\Router\Http\RouteMatch', $match);
        }
    }

    public function testMatch_GetParams()
    {
        $controller = 'controllerFunc';
        $expected = ['controller' => $controller, 'param1' => 'param1', 'param2' => 'param2'];

        $uri = '/home/@param1/@param2';
        $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($uri);
        $route->setController($controller);

        $match = $router->match();
        $this->assertSame($expected, $match->getParams());
    }

    public function testMatch_GetController()
    {
        $controller = 'controllerFunc';

        $uri = $this->generateRandomRoute();
        $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($uri);
        $route->setController($controller);

        $match = $router->match();
        $this->assertInstanceOf('\Zend\Mvc\Router\Http\RouteMatch', $match);
        $this->assertSame($controller, $match->getParam('controller'));
    }

    public function testMatch_NoMethodsMatch()
    {
        $uri = $this->generateRandomRoute();
        $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($uri);
        $route->setMethods([]);

        $match = $router->match();
        $this->assertFalse($match);
    }

    public function testMatch_ConstraintsMatch()
    {
        $uri = '/home/@param1/@param2';
        $_SERVER['REQUEST_URI'] = '/home/TEST/101';

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($uri);
        $route->setConstraints(['param1' => '[A-Z]+', 'param2' => '[0-9]+']);

        $match = $router->match();
        $this->assertInstanceOf('\Zend\Mvc\Router\Http\RouteMatch', $match);
    }

    public function testMatch_ConstraintsDoNotMatch()
    {
        $uri = '/home/@param1/@param2';
        $_SERVER['REQUEST_URI'] = '/home/101/TEST';

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($uri);
        $route->setConstraints(['param1' => '[A-Z]+', 'param2' => '[0-9]+']);

        $match = $router->match();
        $this->assertFalse($match);
    }

    public function testMatch_NoRouteMatch()
    {
        $_SERVER['REQUEST_URI'] = str_replace('@', '', $this->generateRandomRoute());

        $router = $this->getRouterService();
        $route = $router->addRoute($this->generateRandomName());
        $route->setRoute($this->generateRandomRoute());

        $match = $router->match();
        $this->assertFalse($match);
    }

    /**
     * TEST HELPER
     *
     * Get Router Service Object
     *
     * @return RouterService
     */
    private function getRouterService()
    {
        $service = new RouterService(new Request);
        $this->assertInstanceOf('\CPCCore\Router\RouterService', $service);
        return $service;
    }

    /**
     * TEST HELPER
     *
     * Generate Invalid characters
     * Return a string Ascii 32-124 NOT containing allowed characters
     *
     * @return string
     */
    private function generateInvalidCharacters($allowed)
    {
        $invalid = '';
        for ($i = 32; $i < 126; $i++) {
            $ascii = chr($i);
            if (!strstr($allowed, $ascii)) {
                $invalid .= $ascii;
            }
        }
        return $invalid;
    }

    /**
     * TEST HELPER
     *
     * Generate a random Routename
     *
     * Name must be 4-32 characters a-zA-z0-9-
     *
     * @return string
     */
    private function generateRandomName($nameLength = null, $injectInvalidCharacter = false)
    {
        $allowed = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-";
        $allowedLastCharacter = strlen($allowed) - 1;

        if (is_null($nameLength)) {
            $nameLength = rand(4, 32);
        }

        $name = '';
        for ($f = 0; $f < $nameLength; $f++) {
            $name .= $allowed[rand(0, $allowedLastCharacter)];
        }

        if ($injectInvalidCharacter) {
            $invalid = $this->generateInvalidCharacters($allowed);
            $name[rand(1, strlen($name) - 1)] = $invalid[rand(0, strlen($invalid) - 1)];
        }

        return $name;
    }

    /**
     * TEST HELPER
     *
     * Generate a route
     */
    private function generateRandomRoute($injectInvalidCharacter = false)
    {
        $allowed = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-";
        $maxAllowed = strlen($allowed) - 1;

        $route = '';
        $numberofParts = rand(2, 20);
        for ($i = 0; $i < $numberofParts; $i++) {
            $param = '/';

            // Is variable?
            if ($i == 1) {
                $param .= '@';
            } elseif ((bool) rand(0, 1)) {
                $param .= '@';
            }

            $lParam = rand(2, 10);
            $name = '';
            for ($l = 0; $l < $lParam; $l++) {
                $name .= $allowed[rand(0, $maxAllowed)];
            }

            if ($injectInvalidCharacter) {
                $invalid = $this->generateInvalidCharacters($allowed);
                $name[rand(1, strlen($name) - 1)] = $invalid[rand(0, strlen($invalid) - 1)];
            }

            $route .= $param . $name;
        }

        // Add endslash?
        if ((bool) rand(0, 1)) {
            $route .= '/';
        }

        return $route;
    }

}
