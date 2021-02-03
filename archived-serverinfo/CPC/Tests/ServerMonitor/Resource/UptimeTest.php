<?php
namespace ServerMonitor\Tests;

use CPC\ServerMonitor\Resource;
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'TestHelpers/ShellExec.php';

/**
 * Uptime test case.
 */
class UptimeTest extends \PHPUnit_Framework_TestCase
{

    private $Uptime;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Uptime = new Resource\Uptime();
        $this->arrResults = $this->Uptime->getData();
    }

    protected function tearDown()
    {
        $this->Uptime = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Uptime', $this->Uptime);
        $this->assertInternalType('array', $this->Uptime->getData());
    }

    /**
     * Test Root Elements exist
     * Test Root Elements are of correct type
     * Test Root Elements count is correct
     */
    public function testGetData_Elements()
    {
        $template = array(
            'status' => 'string',
            'resource' => 'string',
            'description' => 'string',
            'data' => 'array'
        );

        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $this->arrResults, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $this->arrResults[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $this->arrResults, "[ Incorrect number of elements ]");
        $this->assertEquals('okay', $this->arrResults['status']);
        $this->assertEquals('Uptime', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'cpu_cores' => 'int',
            'uptime' => 'array',
            'idletime_total' => 'array',
            'idletime_per_core' => 'array'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data_Uptime()
    {
        $template = array(
            'time' => 'int',
            'days' => 'int',
            'hours' => 'int',
            'minutes' => 'int'
        );

        $element = $this->arrResults['data']['uptime'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data_Idletime()
    {
        $template = array(
            'time' => 'int',
            'days' => 'int',
            'hours' => 'int',
            'minutes' => 'int'
        );

        $element = $this->arrResults['data']['idletime_total'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data_idletimePerCore()
    {
        $template = array(
            'time' => 'int',
            'days' => 'int',
            'hours' => 'int',
            'minutes' => 'int'
        );

        $this->arrResults = $this->Uptime->getData();
        $element = $this->arrResults['data']['idletime_per_core'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }


    /**
     * Test Get Number of Processor Cores Data Exception
     *
     * Invalid Core Data Returned
     */
    public function testGetNumberOfCpuCores_ResourceException()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Uptime', 'getNumberOfCpuCores');
        $method->setAccessible(true);
        $result = $method->invoke($this->Uptime);
    }

    /**
     * Test Get getTimeInfo Data Exception
     *
     * Invalid Time Data Returned
     */
    public function testGetTimeInfo_ResourceException()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Uptime', 'getTimeInfo');
        $method->setAccessible(true);
        $result = $method->invoke($this->Uptime);
    }



}
