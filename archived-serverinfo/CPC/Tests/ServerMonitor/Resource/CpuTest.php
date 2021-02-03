<?php
namespace ServerMonitor\Tests;

use CPC\ServerMonitor\Resource;
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'TestHelpers/ShellExec.php';

/**
 * Cpu test case.
 */
class CpuTest extends \PHPUnit_Framework_TestCase
{

    private $Cpu;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Cpu = new Resource\Cpu();
        $this->arrResults = $this->Cpu->getData();
    }

    protected function tearDown()
    {
        $this->Cpu = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Cpu', $this->Cpu);
        $this->assertInternalType('array', $this->Cpu->getData());
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
        $this->assertEquals('Cpu', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'architecture' => 'string',
            'cpu_op_modes' => 'string',
            'cpus' => 'int',
            'threads_per_core' => 'int',
            'cores_per_socket' => 'int',
            'sockets' => 'int',
            'vendor_id' => 'string',
            'cpu_family' => 'int',
            'model' => 'int',
            'cpu_mhz' => 'int'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Get Cpu Data Exception
     *
     * Invalid Cpu Data Returned
     */
    public function testGetMemoryInfo_ResourceException()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Cpu', 'getCpuData');
        $method->setAccessible(true);
        $result = $method->invoke($this->Cpu);
    }
}
