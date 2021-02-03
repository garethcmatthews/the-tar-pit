<?php
require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Resource;

/**
 * Php test case.
 */
class PhpTest extends PHPUnit_Framework_TestCase
{

    private $Php;

    private $arrResults;

    protected function setUp()
    {
        parent::setUp();
        $this->Php = new Resource\Php();
        $this->arrResults = $this->Php->getData();
    }

    protected function tearDown()
    {
        $this->Php = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     * Test Resource return type
     */
    public function testGetData_Types()
    {
        $this->assertInstanceOf('CPC\ServerMonitor\Resource\Php', $this->Php);
        $this->assertInternalType('array', $this->Php->getData());
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
        $this->assertEquals('Php', $this->arrResults['resource']);
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     */
    public function testGetData_Elements_Data()
    {
        $template = array(
            'zend_version' => 'string',
            'version' => 'string',
            'extensions' => 'array',
            'zend_extensions' => 'array'
        );

        $element = $this->arrResults['data'];
        foreach ($template as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
        }

        $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
    }

    /**
     * Test Zend Version
     */
    public function testGetData_Elements_Data_ZendVersion()
    {
        $this->assertEquals(zend_version(), $this->arrResults['data']['zend_version']);
    }

    /**
     * Test Php Version
     */
    public function testGetData_Elements_Data_PhpVersion()
    {
        $this->assertEquals(phpversion(), $this->arrResults['data']['version']);
    }

    /**
     * Test Extensions
     */
    public function testGetData_Elements_Data_Extensions()
    {
        // Get Test Data
        $data = array();
        $extensions = get_loaded_extensions();
        foreach ($extensions as $extension) {
            $data[$extension] = phpversion($extension);
        }

        // Check Count
        $element = $this->arrResults['data']['extensions'];
        $this->assertCount(count($data), $element);

        // Check Types
        foreach ($data as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertEquals($element[$key], $value, "[ Key '$key' incorrect data ]");
        }
    }

    /**
     * Test Zend Extensions
     */
    public function testGetData_Elements_Data_ZendExtensions()
    {
        // Get Test Data
        $data = array();
        $extensions = get_loaded_extensions(true);
        foreach ($extensions as $extension) {
            $data[$extension] = phpversion($extension);
        }

        // Check Count
        $element = $this->arrResults['data']['zend_extensions'];
        $this->assertCount(count($data), $element);

        // Check Types
        foreach ($data as $key => $value) {
            $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
            $this->assertEquals($element[$key], $value, "[ Key '$key' incorrect data ]");
        }
    }
}
