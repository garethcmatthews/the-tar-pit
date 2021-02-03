<?php

namespace CPCCoreIntegrationTest\Application\Application;

use \CPCCore\Application\ApplicationService;

class ApplicationServiceErrorPagesTest extends \PHPUnit_Framework_TestCase
{

    const RANDOMISED_TEST_LOOPS = 25;

    public function testRouteMatch_NoMatch_GeneratesHTTP404Response()
    {
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $this->generateRandomRoute());

            $application = $this->getApplicationService();
            $route = $application->route($this->generateRandomName());
            $route->setRoute($this->generateRandomRoute());
            $route->setController(function() {

            });

            $application->run();
            $this->assertSame(404, $application->getResponse()->getStatusCode());
            $this->assertSame('404 Error Page Not Found', $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_NoMatch_GeneratesCustomHTTP404Response()
    {
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $this->generateRandomRoute());

            $application = $this->getApplicationService();
            $application->setError404(function($response) {
                $response->setContent('Custom Error Message');
            });

            $route = $application->route($this->generateRandomName());
            $route->setRoute($this->generateRandomRoute());
            $route->setController(function() {

            });

            $application->run();
            $this->assertSame(404, $application->getResponse()->getStatusCode());
            $this->assertSame('Custom Error Message', $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_NoController_GeneratesHTTP500Response()
    {
        $routeName = $this->generateRandomName();
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $route = $application->route($routeName);
            $route->setRoute($uri);
            $application->run();

            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertSame(sprintf('Route "%s" - Controller function cannot be found or is not callable.', $routeName), $application->getResponse()->getContent());
            ob_clean();
            unset($application, $route);
        }
    }

    public function testRouteMatch_NoController_GeneratesCustomHTTP500Response()
    {
        $routeName = $this->generateRandomName();
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $_SERVER['REQUEST_URI'] = str_replace('@', '', $uri);

            $application = $this->getApplicationService();
            $application->setError500(function($response) {
                $response->setContent('Custom Error Message');
            });

            $route = $application->route($routeName);
            $route->setRoute($uri);
            $application->run();

            $this->assertSame(500, $application->getResponse()->getStatusCode());
            $this->assertSame('Custom Error Message', $application->getResponse()->getContent());
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
