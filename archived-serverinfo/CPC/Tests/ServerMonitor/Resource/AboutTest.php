<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * About test case.
 */
class AboutTest extends PHPUnit_Framework_TestCase
{

    private $About;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->About = new Resource\About();
        $this->arrResults = $this->About->getData();
    }

    protected function tearDown()
    {
        $this->About = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\About', $this->About);
        $this->assertInternalType('array', $this->About->getData());
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
        $this->assertEquals('About', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'title' => 'string',
            'version' => 'float'
        );
        
        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }
        
        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }
}
