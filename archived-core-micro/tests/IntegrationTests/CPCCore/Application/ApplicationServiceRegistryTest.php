<?php

namespace CPCCoreIntegrationTest\Application\Application;

use \CPCCore\Application\ApplicationService;

class ApplicationServiceRegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testRouteRegistry_SetterGetter()
    {
        $key1 = 'item1';
        $key2 = 'item2';
        $key3 = 'item3';

        $value1 = 'value1';
        $value2 = ['value1', 'value2', 'value3'];
        $value3 = function() {
            
        };

        $service = $this->getApplicationService();
        $this->assertTrue($service->setRegistry($key1, $value1));
        $this->assertTrue($service->setRegistry($key2, $value2));
        $this->assertTrue($service->setRegistry($key3, $value3));
        $this->assertSame($value1, $service->getRegistry($key1));
        $this->assertSame($value2, $service->getRegistry($key2));
        $this->assertSame($value3, $service->getRegistry($key3));
        $service->run();
        ob_clean();
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

}
