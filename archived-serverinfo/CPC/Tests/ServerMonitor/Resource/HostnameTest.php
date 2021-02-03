<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

require_once 'TestHelpers/GetHostname.php';

/**
 * Hostname test case.
 */
class HostnameTest extends PHPUnit_Framework_TestCase
{

    private $Hostname;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Hostname = new Resource\Hostname();
        $this->arrResults = $this->Hostname->getData();
    }

    protected function tearDown()
    {
        $thisHostname = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Hostname', $this->Hostname);
        $this->assertInternalType('array', $this->Hostname->getData());
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
        $this->assertEquals('Hostname', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'hostname' => 'string'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Hostname
     */
    public function testElement_data_hostname()
    {
        $this->assertEquals(gethostname(), $this->arrResults['data']['hostname'], '[ Hostname is Invalid ]');
    }

    /**
     * Test Get Hostname Exception
     *
     * Invalid Hostname Returned
     */
    public function testGetData_ResourceException_Gethostname()
    {
        global $mockGetHostname;
        $this->setExpectedException('CPC\ServerMonitor\Core\Exception\ResourceException');
        $mockGetHostname = true;
        $this->Hostname->getData();
    }
}
