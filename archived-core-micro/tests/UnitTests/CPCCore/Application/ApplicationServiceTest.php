<?php

namespace CPCCoreUnitTest\Application;

use \CPCCore\Application\Factory\ApplicationFactory;
use \CPCCore\Application\ApplicationService;
use \CPCCore\Application\Model\CallbacksModel;
use \CPCCore\Application\Model\RegistryModel;
use \CPCCore\Router\RouterService;
use \Zend\Http\PhpEnvironment\Request;
use \Zend\Http\PhpEnvironment\Response;

class ApplicationServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testAddAfterRouteCallback()
    {
        $callbacks = $this->getMockCallbacksModel();
        $callbacks->expects($this->once())
                ->method('add')
                ->will($this->returnValue(true));
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $callbacks, $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $func = function() {

        };

        $this->assertTrue($service->addAfterRouteCallback($func));
    }

    public function testAddBeforeRouteCallback()
    {
        $callbacks = $this->getMockCallbacksModel();
        $callbacks->expects($this->once())
                ->method('add')
                ->will($this->returnValue(true));

        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $callbacks, $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $func = function() {

        };

        $this->assertTrue($service->addBeforeRouteCallback($func));
    }

    public function testSetRegistry()
    {
        $registry = $this->getMockRegistryModel();
        $registry->expects($this->once())
                ->method('set')
                ->with($this->isType('string'), $this->anything())
                ->will($this->returnValue(true));

        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $registry);
        $service = $this->getService($factory);

        $this->assertTrue($service->setRegistry('key', 'value'));
    }

    public function testSetRegistry_ReservedKey_ThrowsException()
    {
        $reservedKey = 'error-pages';
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationRegistryException', sprintf('Registry key invalid "%s". Keys starting with the string "error-" are reserved.', $reservedKey));
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $service->setRegistry($reservedKey, 'myValue');
    }

    public function testGetRegistry()
    {
        $registry = $this->getMockRegistryModel();
        $registry->expects($this->once())
                ->method('set')
                ->with($this->isType('string'), $this->anything())
                ->will($this->returnValue(true));
        $registry->expects($this->once())
                ->method('get')
                ->with($this->isType('string'))
                ->will($this->returnValue(true));

        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $registry);
        $service = $this->getService($factory);

        $this->assertTrue($service->setRegistry('key', 'value'));
        $this->assertTrue($service->getRegistry('key'));
    }

    public function testGetRequest()
    {
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Request', $service->getRequest());
    }

    public function testGetResponse()
    {
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $service->getResponse());
    }

    public function testSetBasePath()
    {
        $request = $this->getMockRequest();
        $request->expects($this->once())
                ->method('setBasePath')
                ->with($this->isType('string'));
        $factory = $this->getMockFactory($request, $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);

        $this->assertTrue($service->setBasePath('/base'));
    }

    public function testSetBasePath_PathNotString_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationServiceException', 'Base path is empty or is not a string.');
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $service->setBasePath([]);
    }

    public function testSetBasePath_PathEmpty_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationServiceException', 'Base path is empty or is not a string.');
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $service->setBasePath('');
    }

    public function testRoute_ReturnsRouteFacade()
    {
        $router = $this->getMockRouterService();
        $router->expects($this->once())
                ->method('addRoute')
                ->with($this->isType('string'))
                ->will($this->returnValue($this->getMockRouteFacade()));
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $router, $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);

        $this->assertInstanceOf('\CPCCore\Router\Route\Facade\RouteFacade', $service->route('route-name'));
    }

    public function testAddRoutes_RouteNameIsNotString_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationRouteException', 'Route name is not a string.');
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $service->route([]);
    }

    public function testSetError404()
    {
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertTrue($service->setError404(function() {

                }));
    }

    public function testSetError404_NotCallable_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationServiceException', 'Application error HTTP404 callback is not callable.');
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertTrue($service->setError404('NotCallable'));
    }

    public function testSetError500()
    {
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertTrue($service->setError500(function() {

                }));
    }

    public function testSetError500_NotCallable_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationServiceException', 'Application error HTTP500 callback is not callable.');
        $factory = $this->getMockFactory($this->getMockRequest(), $this->getMockResponse(), $this->getMockRouterService(), $this->getMockCallbacksModel(), $this->getMockRegistryModel());
        $service = $this->getService($factory);
        $this->assertTrue($service->setError500('NotCallable'));
    }

    /**
     * TEST HELPER
     *
     * Get Application Service
     *
     * @return ApplicationService
     */
    private function getService(\CPCCore\Application\Factory\ApplicationFactory $factory)
    {
        $service = new ApplicationService($factory);
        $this->assertInstanceOf('\CPCCore\Application\ApplicationService', $service);
        return $service;
    }

    /**
     * TEST HELPER
     *
     * Get Factory Mock
     *
     * @param CallbacksModel $model
     * @param Request $request
     * @param Response $response
     * @param RouterService $router
     * @return ApplicationFactory
     */
    private function getMockFactory(Request $request, Response $response, RouterService $router, CallbacksModel $callbacks, RegistryModel $registry)
    {
        $factory = $this->getMockBuilder('\CPCCore\Application\Factory\ApplicationFactory')
                ->setMethods(['createRequest', 'createResponse', 'createRouterService', 'createCallbacksModel', 'createRegistryModel'])
                ->getMock();
        $factory->expects($this->once())
                ->method('createRequest')
                ->will($this->returnValue($request));
        $factory->expects($this->once())
                ->method('createResponse')
                ->will($this->returnValue($response));
        $factory->expects($this->once())
                ->method('createRouterService')
                ->will($this->returnValue($router));
        $factory->expects($this->exactly(2))
                ->method('createCallbacksModel')
                ->will($this->returnValue($callbacks));
        $factory->expects($this->once())
                ->method('createRegistryModel')
                ->will($this->returnValue($registry));
        return $factory;
    }

    /**
     * TEST HELPER
     *
     * Get Callbacks Model Mock
     */
    private function getMockCallbacksModel()
    {
        return $this->getMockBuilder('\CPCCore\Application\Model\CallbacksModel')
                        ->setMethods(['add', 'execute'])
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Registry Model Mock
     */
    private function getMockRegistryModel()
    {
        return $this->getMockBuilder('\CPCCore\Application\Model\RegistryModel')
                        ->setMethods(['set', 'get'])
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Request Mock
     */
    private function getMockRequest()
    {
        return $this->getMockBuilder('\Zend\Http\PhpEnvironment\Request')
                        ->setMethods(['setBasePath'])
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Response Mock
     */
    private function getMockResponse()
    {
        return $this->getMockBuilder('\Zend\Http\PhpEnvironment\Response')->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Router Service Mock
     */
    private function getMockRouterService()
    {
        return $this->getMockBuilder('\CPCCore\Router\RouterService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    /**
     * TEST HELPER
     *
     * Get Route Facade Mock
     */
    private function getMockRouteFacade()
    {
        return $this->getMockBuilder('\CPCCore\Router\Route\Facade\RouteFacade')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

}
