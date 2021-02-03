<?php

namespace CPCCoreUnitTest\Router\Factory;

use \CPCCore\Router\Factory\RouterFactory;

class RouterFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRouteModel()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\CPCCore\Router\Route\Model\RouteModel', $factory->createRouteModel('route-name'));
    }

    public function testCreateRouteFacade()
    {
        $route = $this->getMockRouteModel();
        $factory = $this->getFactory();
        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $factory->createRouteFacade($route));
    }

    public function testCreateSegmentRoute()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\Zend\Mvc\Router\Http\Segment', $factory->createSegmentRoute('/', 'controller', []));
    }

    /**
     * TEST HELPER
     *
     * Get Factory Object
     *
     * @return RouterFactory
     */
    private function getFactory()
    {
        $factory = new RouterFactory();
        $this->assertInstanceOf('\CPCCore\Router\Factory\RouterFactory', $factory);
        return $factory;
    }

    /**
     * TEST HELPER
     *
     * Get RouteModel Mock
     */
    private function getMockRouteModel()
    {
        return $this->getMockBuilder('\CPCCore\Router\Route\Model\RouteModel')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

}
