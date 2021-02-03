<?php
ob_start();

require_once 'PHPUnit/Framework/TestCase.php';

use CPC\ServerMonitor\Core\Exception;

/**
 * About test case.
 */
class ApplicationExceptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test Exception Message
     */
    public function testException_message()
    {
        try {
            throw new Exception\ApplicationException('Testing');
        } catch (Exception\ApplicationException $e) {
            $this->assertEquals('Application Error: Testing', $e->getMessage());
        }
    }

    /**
     * Test Exception Render Response
     * Test Response Code
     */
    public function testException_renderResponseCode()
    {
        try {
            throw new Exception\ApplicationException('Testing');
        } catch (Exception\ApplicationException $e) {
            $response = $e->renderResponse();
            $this->assertEquals(404, http_response_code());
        }
    }

    /**
     * Test Root Elements exist
     * Test Root Elements are of correct type
     * Test Root Elements count is correct
     * Test Root Elements 'status' value is correct
     */
    public function testException_renderResponseJson()
    {
        $template = array(
            'status' => 'string',
            'data' => 'array'
        );

        try {
            throw new Exception\ApplicationException('Testing');
        } catch (Exception\ApplicationException $e) {

            $response = json_decode($e->renderResponse(), true);
            foreach ($template as $key => $value) {
                $this->assertArrayHasKey($key, $response, "[ Missing Key '$key' ]");
                $this->assertInternalType($value, $response[$key], "[ Key '$key' Invalid Type ]");
            }

            $this->assertCount(count($template), $response, "[ Incorrect number of elements ]");
            $this->assertEquals('error', $response['status']);
        }
    }

    /**
     * Test Data Elements exist
     * Test Data Elements are of correct type
     * Test Data Elements count is correct
     * Test Data Elements 'error' value is correct
     */
    public function testException_renderResponseJson_data()
    {
        $template = array(
            'error' => 'string',
            'description' => 'string'
        );

        try {
            throw new Exception\ApplicationException('Testing');
        } catch (Exception\ApplicationException $e) {

            $response = json_decode($e->renderResponse(), true);
            $element = $response['data'];
            foreach ($template as $key => $value) {
                $this->assertArrayHasKey($key, $element, "[ Missing Key '$key' ]");
                $this->assertInternalType($value, $element[$key], "[ Key '$key' Invalid Type ]");
            }

            $this->assertCount(count($template), $element, "[ Incorrect number of elements ]");
            $this->assertEquals('application-error', $element['error']);
        }
    }
}
