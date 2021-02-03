<?php

namespace CPCCoreIntegrationTest\Application\Application;

use \CPCCore\Application\ApplicationService;

class ApplicationServiceCallbacksTest extends \PHPUnit_Framework_TestCase
{

    public function testRouteCallbacks()
    {
        $before1 = $this->getMock('stdClass', array('Before1'));
        $before1->expects($this->once())
                ->method('Before1')
                ->will($this->returnValue(true));
        $before2 = $this->getMock('stdClass', array('Before2'));
        $before2->expects($this->once())
                ->method('Before2')
                ->will($this->returnValue(true));
        $before3 = $this->getMock('stdClass', array('Before3'));
        $before3->expects($this->once())
                ->method('Before3')
                ->will($this->returnValue(true));

        $after1 = $this->getMock('stdClass', array('After1'));
        $after1->expects($this->once())
                ->method('After1')
                ->will($this->returnValue(true));
        $after2 = $this->getMock('stdClass', array('After2'));
        $after2->expects($this->once())
                ->method('After2')
                ->will($this->returnValue(true));
        $after3 = $this->getMock('stdClass', array('After3'));
        $after3->expects($this->once())
                ->method('After3')
                ->will($this->returnValue(true));

        $service = $this->getApplicationService();

        $service->addBeforeRouteCallback(array($before1, 'Before1'));
        $service->addBeforeRouteCallback(array($before2, 'Before2'));
        $service->addBeforeRouteCallback(array($before3, 'Before3'));

        $service->addAfterRouteCallback(array($after1, 'After1'));
        $service->addAfterRouteCallback(array($after2, 'After2'));
        $service->addAfterRouteCallback(array($after3, 'After3'));

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
