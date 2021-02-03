<?php
require_once 'PHPUnit/Framework/TestCase.php';

require_once 'ResourceTraitImplementor.php';

/**
 * About test case.
 */
class ResourceTest extends PHPUnit_Framework_TestCase
{

    private $ResourceTraitImplementor;

    protected function setUp()
    {
        parent::setUp();
        $this->ResourceTraitImplementor = new Tests\Helper\ResourceTraitImplementor();
    }

    protected function tearDown()
    {
        $this->ResourceTraitImplementor = null;
        parent::tearDown();
    }

    /**
     * Test Resource type
     */
    public function testSetupTypes()
    {
        $this->assertInstanceOf('Tests\Helper\ResourceTraitImplementor', $this->ResourceTraitImplementor);
    }

    /**
     * Test Resource Trait
     */
    public function testGetResourceName()
    {
        $this->assertEquals('ResourceTraitImplementor', $this->ResourceTraitImplementor->proxyGetResourceName());
    }
}
