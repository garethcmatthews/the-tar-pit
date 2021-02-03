<?php

namespace CPCCoreUnitTest\Application\Factory;

use CPCCore\Application\Factory\ApplicationFactory;

class ApplicationFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRequest()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Request', $factory->createRequest());
    }

    public function testCreateResponse()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $factory->createResponse());
    }

    public function testCreateRouterService()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\CPCCore\Router\RouterService', $factory->createRouterService());
    }

    public function testCreateCallbacksModel()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\CPCCore\Application\Model\CallbacksModel', $factory->createCallbacksModel());
    }

    public function testCreateRegistryModel()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf('\CPCCore\Application\Model\RegistryModel', $factory->createRegistryModel());
    }

    /**
     * TEST HELPER
     *
     * Get Factory Object
     *
     * @return ApplicationFactory
     */
    private function getFactory()
    {
        $factory = new ApplicationFactory();
        $this->assertInstanceOf('\CPCCore\Application\Factory\ApplicationFactory', $factory);
        return $factory;
    }

}
