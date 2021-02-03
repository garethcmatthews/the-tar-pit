<?php

namespace CPCCoreUnitTest\Application\Model;

use \CPCCore\Application\Model\RegistryModel;

class RegistryModelTest extends \PHPUnit_Framework_TestCase
{

    public function testSet()
    {
        $model = $this->getModel();
        $this->assertTrue($model->set('item1', 'value1'));
    }

    public function testSet_RegistryKeyNotString_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationRegistryException', 'Registry key is not a string or is empty.');
        $model = $this->getModel();
        $model->set([], 'value1');
    }

    public function testSet_RegistryKeyEmpty_ThrowsException()
    {
        $this->setExpectedException('\CPCCore\Application\Exception\ApplicationRegistryException', 'Registry key is not a string or is empty.');
        $model = $this->getModel();
        $model->set('', 'value1');
    }

    public function testGet()
    {
        $key1 = 'item1';
        $key2 = 'item2';
        $key3 = 'item3';

        $value1 = 'value1';
        $value2 = ['value1', 'value2', 'value3'];
        $value3 = function() {

        };

        $model = $this->getModel();

        $this->assertTrue($model->set($key1, $value1));
        $this->assertTrue($model->set($key2, $value2));
        $this->assertTrue($model->set($key3, $value3));

        $this->assertSame($value1, $model->get($key1));
        $this->assertSame($value2, $model->get($key2));
        $this->assertSame($value3, $model->get($key3));
    }

    public function testGet_RegistryKeyDoesNotExists_ThrowsException()
    {
        $model = $this->getModel();
        $this->assertFalse($model->get('NOTFOUND'));
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
        $model = new RegistryModel();
        $this->assertInstanceOf('\CPCCore\Application\Model\RegistryModel', $model);
        return $model;
    }

}
