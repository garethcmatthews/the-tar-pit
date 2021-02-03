<?php

namespace CPCCoreUnitTest\Router\Route;

use \CPCCore\Router\Factory\RouterFactory;
use \CPCCore\Router\RouterService;
use \CPCCore\Router\Route\Facade\RouteFacade;
use \CPCCore\Router\Route\Model\RouteModel;
use \Zend\Http\PhpEnvironment\Request;

class RouterServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testAddRoutes_ReturnsRouteFacade()
    {
        $factory = $this->getMockFactory();
        $factory->expects($this->once())
                ->method('createRouteModel')
                ->will($this->returnValue($this->getMockRouteModel()));
        $factory->expects($this->once())
                ->method('createRouteFacade')
                ->will($this->returnValue($this->getMockRouteFacade()));

        $router = $this->getRouterService($factory);
        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $router->addRoute('route-name'));
    }

    public function testAddRoutes_DuplicateRouteName_ThrowsException()
    {
        $routeName = 'route-name';
        $this->setExpectedException('\CPCCore\Router\Exception\RouterServiceException', sprintf('Route name "%s" is already in use', $routeName));
        $factory = $this->getMockFactory();
        $factory->expects($this->exactly(2))
                ->method('createRouteModel')
                ->will($this->returnValue($this->getMockRouteModel()));
        $factory->expects($this->once())
                ->method('createRouteFacade')
                ->will($this->returnValue($this->getMockRouteFacade()));

        $router = $this->getRouterService($factory);
        $router->addRoute($routeName);
        $router->addRoute($routeName);
    }

    public function testMatch_NoRoutes_ReturnsFalse()
    {
        $factory = $this->getMockFactory();
        $router = $this->getRouterService($factory);
        $this->assertFalse($router->match());
    }

    /**
     * TEST HELPER
     *
     * Get Router Service Object
     *
     * @return RouterService
     */
    private function getRouterService(\CPCCore\Router\Factory\RouterFactory $factory)
    {
        $service = new RouterService($this->getMockRequest(), $factory);
        $this->assertInstanceOf('\CPCCore\Router\RouterService', $service);
        return $service;
    }

    /**
     * TEST HELPER
     *
     * Get Factory Mock
     *
     * @return RouterFactory
     */
    private function getMockFactory()
    {
        return $this->getMockBuilder('\CPCCore\Router\Factory\RouterFactory')
                        ->setMethods(['createRouteModel', 'createRouteFacade'])
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Route Facade Mock
     *
     * @return RouteFacade
     */
    private function getMockRouteFacade()
    {
        return $this->getMockBuilder('\CPCCore\Router\Route\Facade\RouteFacade')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Route Model Mock
     *
     * @return RouteModel
     */
    private function getMockRouteModel()
    {
        return $this->getMockBuilder('\CPCCore\Router\Route\Model\RouteModel')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * @return Request
     */
    private function getMockRequest()
    {
        return $this->getMockBuilder('\Zend\Http\PhpEnvironment\Request')
                        ->getMock();
    }

}
