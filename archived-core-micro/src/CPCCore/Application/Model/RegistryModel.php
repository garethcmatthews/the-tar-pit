<?php

/**
 * CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
 *
 * @author      Gareth C Matthews <garethmatthews911@gmail.com>
 * @copyright   2015 Gareth Matthews
 * @link        https://github.com/CrossPlatformCoder/CPCCore
 * @license     BSD 3-Clause
 * @version     1.0.0
 */

namespace CPCCore\Application\Model;

use CPCCore\Application\Exception\ApplicationRegistryException;

/**
 * Registry
 *
 * Acts as a container for user definable objects
 *
 * @package CPCCore
 * @subpackage Application
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class RegistryModel
{

    /**
     * Registry
     * @var array
     */
    private $registry = [];

    /**
     * Store a Registry Item
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     * @throws ApplicationRegistryException
     */
    public function set($key, $value)
    {
        if (!is_string($key) || empty($key)) {
            throw new ApplicationRegistryException('Registry key is not a string or is empty.');
        }

        $this->registry[$key] = $value;
        return true;
    }

    /**
     * Get a Registry Item
     *
     * @param string $key
     * @return mixed|false
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->registry)) {
            return false;
        }

        return $this->registry[$key];
    }

}
