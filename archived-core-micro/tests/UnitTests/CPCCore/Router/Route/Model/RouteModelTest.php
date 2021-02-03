<?php

namespace CPCCoreUnitTest\Router\Route\Model;

use \CPCCore\Router\Route\Model\RouteModel;

//use CPCCore\Router\Route\Exception\RouteModelException;

class RouterModelTest extends \PHPUnit_Framework_TestCase
{

    const RANDOMISED_TEST_LOOPS = 25;

    /**
     * Test Route name
     *
     * A Route name must be 4-32 characters of [a-zA-Z0-9-]
     */
    public function testName_SetterGetter()
    {
        $name = $this->generateRandomName();
        $model = $this->getModel($name);

        $this->assertSame($name, $model->getName());
    }

    public function testName_IsNotString_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', 'Name is empty or not a string.');
        $this->getModel([]);
    }

    public function testName_IsEmpty_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', 'Name is empty or not a string.');
        $this->getModel('');
    }

    public function testName_InvalidName_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', 'Name must be a valid string [a-zA-Z0-9-] of 4-32 characters.');
        $this->getModel($this->generateRandomName(null, true));
    }

    public function testName_NameTooShort_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', 'Name must be a valid string [a-zA-Z0-9-] of 4-32 characters.');
        $this->getModel($this->generateRandomName(3));
    }

    public function testName_NameTooLong_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', 'Name must be a valid string [a-zA-Z0-9-] of 4-32 characters.');
        $this->getModel($this->generateRandomName(33));
    }

    /**
     * Test Route
     *
     * Valid Route /^(\/{1}|(\/{1}@?([0-9a-zA-Z-]+))+\/?)$/
     * Internally the Token Identifier '@' is changed to ':'
     * to ready the route for the Zend MVC HTTP Segment Router
     */
    public function testRoute_InitialState()
    {
        $model = $this->getModel($this->generateRandomName());
        $route = $model->getRoute();

        $this->assertInternalType('string', $route);
        $this->assertEmpty($route);
    }

    public function testRoute_SetterGetter()
    {
        $model = $this->getModel($this->generateRandomName());
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $uri = $this->generateRandomRoute();
            $expected = str_replace('@', ':', $uri);

            $this->assertTrue($model->setRoute($uri));
            $this->assertSame($expected, $model->getRoute());
        }
    }

    public function testRoute_InvalidRoutes_ThrowException()
    {
        $exceptions = 0;
        for ($i = 0; $i < self::RANDOMISED_TEST_LOOPS; $i++) {
            $name = $this->generateRandomName();
            $model = $this->getModel($name);

            try {
                $model->setRoute($this->generateRandomRoute(true));
            } catch (\CPCCore\Router\Route\Exception\RouteModelException $ex) {
                $exceptions++;
                $this->assertSame(sprintf('Route "%s" - Route definition is invalid.', $name), $ex->getMessage());
            }
        }

        $this->assertEquals(self::RANDOMISED_TEST_LOOPS, $exceptions);
    }

    public function testRoute_IsNotString_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Route is empty or not a string.', $name));
        $model = $this->getModel($name);
        $model->setRoute([]);
    }

    public function testRoute_IsEmpty_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Route is empty or not a string.', $name));
        $model = $this->getModel($name);
        $model->setRoute('');
    }

    /**
     * Test Constraints
     *
     * Constraints are key value pairs
     * Key - Parameter name
     * Value - Valid Regex
     */
    public function testConstraints_InitialState()
    {
        $model = $this->getModel($this->generateRandomName());
        $constraints = $model->getConstraints();

        $this->assertInternalType('array', $constraints);
        $this->assertEmpty($constraints);
    }

    public function testConstraints_SetterGetter()
    {
        $model = $this->getModel($this->generateRandomName());

        $current = [];
        $constraints = ['param1' => '[a-z]+', 'param2' => '[A-Z]+', 'param3' => '[0-9]+', 'param4' => '[a-zA-Z0-9-]+'];
        foreach ($constraints as $constraint) {
            $current[] = $constraint;
            $this->assertTrue($model->setConstraints($current));
            $this->assertSame($current, $model->getConstraints());
        }
    }

    public function testConstraints_InvalidConstraint_ThrowsException()
    {
        $name = $this->generateRandomName();
        $model = $this->getModel($name);

        $exceptions = 0;
        $constraints = ['param1' => '[a-z+', 'param2' => '[A-Z+', 'param3' => '[0-9+', 'param4' => '[a-zA-Z0-9-+'];
        foreach ($constraints as $key => $value) {

            try {
                $model->setConstraints([$key => $value]);
            } catch (\CPCCore\Router\Route\Exception\RouteModelException $ex) {
                $exceptions++;
                $this->assertSame(sprintf('Route "%s" - Invalid constraint "%s" => "%s"', $name, $key, $value), $ex->getMessage());
            }
        }

        $this->assertEquals(count($constraints), $exceptions);
    }

    /**
     * Test Methods
     *
     * The Route initialises with all HTTP methods set
     * This can be filtered down by using setMethods
     *
     */
    public function testMethods_InitialState_HasAllMethods()
    {
        $model = $this->getModel($this->generateRandomName());
        foreach ($this->getAllowedMethods(true) as $method) {
            $this->assertTrue($model->hasMethod($method));
        }
    }

    public function testMethods_SetMethod()
    {
        $model = $this->getModel($this->generateRandomName());

        $methods = [];
        foreach ($this->getAllowedMethods(true) as $method) {
            $methods[] = $method;
            $this->assertTrue($model->setMethods($methods));
        }
    }

    public function testMethods_InvalidMethod_ThrowsException()
    {
        $name = $this->generateRandomName();
        $method = 'INVALID';
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Invalid Method "%s"', $name, $method));
        $model = $this->getModel($name);
        $model->setMethods([$method]);
    }

    /**
     * Test Controller
     */
    public function testController_InitialState()
    {
        $model = $this->getModel($this->generateRandomName());
        $controller = $model->getController();

        $this->assertInternalType('string', $controller);
        $this->assertEmpty($controller);
    }

    public function testController_SetterGetter_Function()
    {
        $controller = 'controller';
        $model = $this->getModel($this->generateRandomName());

        $this->assertTrue($model->setController($controller));
        $this->assertSame($controller, $model->getController());
    }

    public function testController_SetterGetter_Class()
    {
        $controller = ['class' => 'cClass', 'method' => 'mMethod'];
        $model = $this->getModel($this->generateRandomName());

        $this->assertTrue($model->setController($controller));
        $this->assertSame($controller, $model->getController());
    }

    public function testController_ClassParameterMissing_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Controller "class" parameter is Missing.', $name));

        $controller = ['method' => 'mMethod'];
        $model = $this->getModel($name);
        $model->setController($controller);
    }

    public function testController_ClassParameterIsNotString_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Controller "class" parameter is not a string.', $name));

        $controller = ['class' => ['cClass'], 'method' => 'mMethod'];
        $model = $this->getModel($name);
        $model->setController($controller);
    }

    public function testController_ClassMethodParameterMissing_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Controller "method" parameter is Missing.', $name));

        $controller = ['class' => 'cClass'];
        $model = $this->getModel($name);
        $model->setController($controller);
    }

    public function testController_ClassMethodParameterIsNotString_ThrowsException()
    {
        $name = $this->generateRandomName();
        $this->setExpectedException('\CPCCore\Router\Route\Exception\RouteModelException', sprintf('Route "%s" - Controller "method" parameter is not a string.', $name));

        $controller = ['class' => 'cClass', 'method' => ['mMethod']];
        $model = $this->getModel($name);
        $model->setController($controller);
    }

    /**
     * TEST HELPER
     *
     * Get Route Service Object
     *
     * @param string $name
     * @return RouteModel
     */
    private function getModel($name)
    {
        $model = new RouteModel($name);
        $this->assertInstanceOf('\CPCCore\Router\Route\Model\RouteModel', $model);
        return $model;
    }

    /**
     * TEST HELPER
     *
     * @param boolean $shuffle Randomise order of methods
     * @return array
     */
    public function getAllowedMethods($shuffle = false)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];
        if ($shuffle) {
            shuffle($methods);
        }
        return $methods;
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
