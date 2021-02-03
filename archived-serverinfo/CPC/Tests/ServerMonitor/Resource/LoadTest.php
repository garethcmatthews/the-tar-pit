<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * Load test case.
 */
class LoadTest extends PHPUnit_Framework_TestCase
{

    private $Load;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Load = new Resource\Load();
        $this->arrResults = $this->Load->getData();
    }

    protected function tearDown()
    {
        $this->Load = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Load', $this->Load);
        $this->assertInternalType('array', $this->Load->getData());
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
        $this->assertEquals('Load', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Types_Elements_Data()
    {
        $template = array(
            'last_one_minute' => 'float',
            'last_five_minutes' => 'float',
            'last_fifteen_minutes' => 'float'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }
}
