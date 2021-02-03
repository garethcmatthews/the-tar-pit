<?php

namespace CPCCoreUnitTest\Router\Route;

use \CPCCore\Router\Route\Facade\RouteFacade;

class RouteFacadeTest extends \PHPUnit_Framework_TestCase
{

    public function testSetConstraints()
    {
        $model = $this->getMockRouteModel();
        $model->expects($this->once())
                ->method('setConstraints')
                ->with($this->isType('array'));
        $facade = $this->getService($model);

        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $facade->setConstraints([]));
    }

    public function testSetController()
    {
        $model = $this->getMockRouteModel();
        $model->expects($this->once())
                ->method('setController')
                ->with($this->anything());
        $facade = $this->getService($model);

        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $facade->setController('controller'));
    }

    public function testSetMethods()
    {
        $model = $this->getMockRouteModel();
        $model->expects($this->once())
                ->method('setMethods')
                ->with($this->isType('array'));
        $facade = $this->getService($model);

        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $facade->setMethods([]));
    }

    public function testSetRoute()
    {
        $model = $this->getMockRouteModel();
        $model->expects($this->once())
                ->method('setRoute')
                ->with($this->isType('string'));
        $facade = $this->getService($model);

        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $facade->setRoute('/'));
    }

    /**
     * TEST HELPER
     *
     * Get Route Facade Object
     *
     * @param \CPCCore\Router\Route\Model\RouteModel $model
     * @return RouteFacade
     */
    private function getService(\CPCCore\Router\Route\Model\RouteModel $model)
    {
        $facade = new RouteFacade($model);
        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $facade);
        return $facade;
    }

    /**
     * TEST HELPER
     *
     * Get RouteModel Mock
     */
    private function getMockRouteModel()
    {
        return $this->getMockBuilder('\CPCCore\Router\Route\Model\RouteModel')
                        ->setMethods(['setConstraints', 'setController', 'setMethods', 'setRoute'])
                        ->disableOriginalConstructor()
                        ->getMock();
    }

}
