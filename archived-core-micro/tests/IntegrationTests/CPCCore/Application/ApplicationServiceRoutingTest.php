<?php

namespace CPCCoreIntegrationTest\Application\Application;

use \CPCCore\Application\ApplicationService;

class callbackTest
{

    public function test()
    {
        return true;
    }

    private function badCall()
    {
        return false;
    }

}

class ApplicationServiceRoutingTest extends \PHPUnit_Framework_TestCase
{

    const RANDOMISED_TEST_LOOPS = 25;

    public function testRouteMatch()
    {

        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($this->generateRandomName());
            $route->setRoute($uri);
            $route->setController(function() {

            });

            $application->run();
            $this->assertSame(200, $application->getResponse()->getStatusCode());
            unset($application, $route);
        }
    }

    public function testRouteMatch_BasePath()
    {
        $basePath = '/home';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = $basePath . str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $application->setBasePath($basePath);
            $route = $application->route($this->generateRandomName());
            $route->setRoute($uri);
            $route->setController(function() {

            });

            $application->run();
            $this->assertSame(200, $application->getResponse()->getStatusCode());
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerClass()
    {

        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($this->generateRandomName());
            $route->setRoute($uri);
            $route->setController(['class' => '\CPCCoreIntegrationTest\Application\Application\callbackTest', 'method' => 'test']);

            $application->run();
            $this->assertSame(200, $application->getResponse()->getStatusCode());
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerClass_ReturnsFalse()
    {

        $routeName = 'route-name';
        $methodName = 'badCall';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $route->setController(['class' => '\CPCCoreIntegrationTest\Application\Application\callbackTest', 'method' => $methodName]);

            $application->run();
            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertContains(sprintf('Route "%s" error calling controller method "%s"', $routeName, $methodName), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerClass_ClassDoesNotExist_ThrowsException()
    {
        $routeName = 'route-name';
        $className = 'NotFound';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $route->setController(['class' => $className, 'method' => 'test']);

            $application->run();
            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertContains(sprintf('Route "%s" - Controller Class "%s" not found.', $routeName, $className), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerClass_MethodDoesNotExist_ThrowsException()
    {
        $routeName = 'route-name';
        $methodName = 'notFound';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $route->setController(['class' => '\CPCCoreIntegrationTest\Application\Application\callbackTest', 'method' => $methodName]);

            $application->run();
            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertContains(sprintf('Route "%s" - Controller Method "%s" does not exist.', $routeName, $methodName), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerFunction_NotFound_ThrowsException()
    {
        $routeName = 'route-name';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $route->setController('NotFound');

            $application->run();
            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertContains(sprintf('Route "%s" - Controller function cannot be found or is not callable.', $routeName), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_ControllerFunction_ReturnsFalse_ThrowsException()
    {
        $routeName = 'route-name';
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $route->setController(function() {
                return false;
            });

            $application->run();
            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertContains(sprintf('Route "%s" error calling controller function', $routeName), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    /**
     * TEST HELPER
     *
     * Get Application Service Object
     *
     * @return ApplicationService
     */
    private function getApplicationService()
    {
        $service = new ApplicationService();
        $this->assertInstanceOf('\CPCCore\Application\ApplicationService', $service);
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
