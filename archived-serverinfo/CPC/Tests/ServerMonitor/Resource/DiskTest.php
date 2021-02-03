<?php
namespace ServerMonitor\Tests;

use CPC\ServerMonitor\Resource;
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'TestHelpers/ShellExec.php';

/**
 * Disk test case.
 */
class DiskTest extends \PHPUnit_Framework_TestCase
{

    private $oDisk;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Disk = new Resource\Disk();
        $this->arrResults = $this->Disk->getData();
    }

    protected function tearDown()
    {
        $this->Disk = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Disk', $this->Disk);
        $this->assertInternalType('array', $this->arrResults);
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
        $this->assertEquals('Disk', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'filesystem' => 'string',
            'size' => 'string',
            'used' => 'string',
            'avail' => 'string',
            'percentage_used' => 'string',
            'mounted_on' => 'string'
        );

        $elements = $this->arrResults['data'];
        foreach ($elements as $element) {
            foreach ($template as $key => $value) {
                $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
                $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
            }

            $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
        }
    }

    /**
     * Test Get Disk Data Exception
     *
     * No Data From Shell Exec
     */
    public function testGetData_ResourceException_Shell()
    {
        global $mockShellExec;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockShellExec = true;

        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Disk', 'getDiskInfo');
        $method->setAccessible(true);
        $result = $method->invoke($this->Disk);
    }

    /**
     * Test Extract Disks
     */
    public function testExtractDisks()
    {
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Disk', 'extractDisks');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->Disk, array(
            "0 ,0, 0\n0 ,0, 0"
        ));

        $this->assertInternalType('array', $result);
        $this->assertEquals(2, count($result));
    }

    /**
     * Test Extract Disks
     *
     * Invalid Disk Data Returned
     */
    public function testExtractDisks_ResourceException()
    {
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Disk', 'extractDisks');
        $method->setAccessible(true);
        $method->invokeArgs($this->Disk, array(
            "0"
        ));
    }

    /**
     * Test Extract Disk Element
     */
    public function testExtractDiskElement()
    {
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Disk', 'ExtractDiskElement');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->Disk, array(
            '0 0 0 0 0 0'
        ));

        $this->assertInternalType('array', $result);
        $this->assertEquals(6, count($result));
    }

    /**
     * Test Extract Disk Element
     *
     * Invalid Disk Data Returned
     */
    public function testExtractDiskElement_ResourceException()
    {
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $method = new \ReflectionMethod('CPC\ServerMonitor\Resource\Disk', 'ExtractDiskElement');
        $method->setAccessible(true);
        $method->invokeArgs($this->Disk, array(
            '0 0'
        ));
    }
}
