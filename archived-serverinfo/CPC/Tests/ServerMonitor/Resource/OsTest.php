<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * Os test case.
 */
class OsTest extends PHPUnit_Framework_TestCase
{

    private $Os;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Os = new Resource\Os();
        $this->arrResults = $this->Os->getData();
    }

    protected function tearDown()
    {
        $this->Os = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Os', $this->Os);
        $this->assertInternalType('array', $this->Os->getData());
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

        $this->arrResults = $this->Os->getData();
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $this->arrResults, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $this->arrResults[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $this->arrResults, "[ Incorrect number of elements ]");
        $this->assertEquals('okay', $this->arrResults['status']);
        $this->assertEquals('Os', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'operating_system' => 'string',
            'hostname' => 'string',
            'release' => 'string',
            'version' => 'string',
            'machine_type' => 'string'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test the OS Data
     */
    public function testGetData_Elements_Data_Match()
    {
        $details = php_uname();
        $info = explode(' ', $details);

        $element = $this->arrResults['data'];
        $this->assertEquals($info[0], $element['operating_system']);
        $this->assertEquals($info[1], $element['hostname']);
        $this->assertEquals($info[2], $element['release']);
        $this->assertEquals($info[3], $element['version']);
        $this->assertEquals($info[4], $element['machine_type']);
    }
}
