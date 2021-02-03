<?php
namespace ServerMonitor\Tests;

use CPC\ServerMonitor\Resource;
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'TestHelpers/ShellExec.php';

/**
 * Memory test case.
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{

    private $Memory;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Memory = new Resource\Memory();
        $this->arrResults = $this->Memory->getData();
    }

    protected function tearDown()
    {
        $this->Memory = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testSetupTypes()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Memory', $this->Memory);
        $this->assertInternalType('array', $this->Memory->getData());
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
        $this->assertEquals('Memory', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'total' => 'int',
            'free' => 'int',
            'used' => 'int'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Extract Value
     */
    public function testExtractValue()
    {
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Memory', 'extractValue');
        $method->setAccessible(true);

        $testData = "Disk: 500 kB cpu: 100Kb";
        $this->assertEquals(500, $method->invokeArgs($this->Memory, array(
            $testData,
            'Disk'
        )));
        $this->assertEquals(100, $method->invokeArgs($this->Memory, array(
            $testData,
            'cpu'
        )));
        $this->assertFalse($method->invokeArgs($this->Memory, array(
            $testData,
            'disk1'
        )));
    }

    /**
     * Test Get Memory Data Exception
     *
     * Invalid Memory Data Returned
     */
    public function testGetMemoryInfo_ResourceException()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Memory', 'getMemoryInfo');
        $method->setAccessible(true);
        $result = $method->invoke($this->Memory);
    }
}
