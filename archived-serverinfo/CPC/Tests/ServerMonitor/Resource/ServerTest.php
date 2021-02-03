<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * Server test case.
 */
class ServerTest extends PHPUnit_Framework_TestCase
{

    private $Server;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Server = new Resource\Server();
        $this->arrResults = $this->Server->getData();
    }

    protected function tearDown()
    {
        $this->Server = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Server', $this->Server);
        $this->assertInternalType('array', $this->Server->getData());
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

        $this->arrResults = $this->Server->getData();
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $this->arrResults, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $this->arrResults[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $this->arrResults, "[ Incorrect number of elements ]");
        $this->assertEquals('okay', $this->arrResults['status']);
        $this->assertEquals('Server', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'software' => 'string',
            'name' => 'string',
            'address' => 'string',
            'port' => 'string'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
            $this->assertEquals('unknown', $element[$key]);
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }
}
