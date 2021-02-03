<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * Time test case.
 */
class TimeTest extends PHPUnit_Framework_TestCase
{

    private $Time;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Time = new Resource\Time();
        $this->arrResults = $this->Time->getData();
    }

    protected function tearDown()
    {
        $this->Time = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Time', $this->Time);
        $this->assertInternalType('array', $this->Time->getData());
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
        $this->assertEquals('Time', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'timezone' => 'string',
            'epoch' => 'int',
            'year' => 'int',
            'month' => 'string',
            'month_number' => 'int',
            'day_of_year' => 'int',
            'day_of_month' => 'int',
            'day_of_week_number' => 'int',
            'day_of_week' => 'string',
            'hours' => 'int',
            'minutes' => 'int',
            'seconds' => 'int'
        );
        
        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }
        
        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }
}
