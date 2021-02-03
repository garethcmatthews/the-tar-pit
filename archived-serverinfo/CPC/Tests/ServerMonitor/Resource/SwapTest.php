<?php
namespace ServerMonitor\Tests;

use CPC\ServerMonitor\Resource;
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'TestHelpers/ShellExec.php';

/**
 * Swap test case.
 */
class SwapTest extends \PHPUnit_Framework_TestCase
{

    private $Swap;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Swap = new Resource\Swap();
        $this->arrResults = $this->Swap->getData();
    }

    protected function tearDown()
    {
        $this->Swap = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Swap', $this->Swap);
        $this->assertInternalType('array', $this->Swap->getData());
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
        $this->assertEquals('Swap', $this->arrResults['resource']);
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
            'used' => 'int',
            'free' => 'int'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Get Swap Data Exception
     *
     * Invalid Swap Data Returned
     */
    public function testGetData_ResourceException_ShellInvalidData()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Swap', 'getSwapInfo');
        $method->setAccessible(true);
        $result = $method->invoke($this->Swap);
    }

    /**
     * Test Get Swap Data Exception
     */
    public function testExtractSwapInfo()
    {
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Swap', 'extractSwapInfo');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->Swap, array(
            'Swap 0 0 0'
        ));

        $this->assertInternalType('array', $result);
        $this->assertEquals(3, count($result));
    }

    /**
     * Test Get Swap Data Exception
     *
     * Invalid Swap Data Returned - Too Few Elements
     */
    public function testExtractSwapInfo_ResourceException()
    {
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Swap', 'extractSwapInfo');
        $method->setAccessible(true);
        $method->invokeArgs($this->Swap, array(
            'Swap 0 0'
        ));
    }
}
