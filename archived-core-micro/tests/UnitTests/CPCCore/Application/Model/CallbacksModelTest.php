<?php

namespace CPCCoreUnitTest\Application\Model;

use \CPCCore\Application\Model\CallbacksModel;

class CallbacksModelTest extends \PHPUnit_Framework_TestCase
{

    public function testAdd()
    {
        $model = $this->getModel();
        $this->assertTrue($model->add(function() {

                }));
    }

    public function testAdd_CallbackFunctionNotCallable_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationCallbackException', 'Callback function cannot be found or is not callable.');
        $model = $this->getModel();
        $model->add('INVALID');
    }

    public function testExecute()
    {
        $model = $this->getModel();
        $model->add(function() {

        });
        $this->assertTrue($model->execute());
    }

    public function testExecute_CallbackFailed_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationCallbackException', 'Route Callback function failed.');
        $model = $this->getModel();
        $model->add(function() {
            return false;
        });
        $model->execute();
    }

    /**
     * TEST HELPER
     *
     * Get Callback Model Object
     *
     * @return CallbacksModel
     */
    private function getModel()
    {
        $model = new CallbacksModel();
        $this->assertInstanceOf('\CPCCore\Application\Model\CallbacksModel', $model);
        return $model;
    }

}
