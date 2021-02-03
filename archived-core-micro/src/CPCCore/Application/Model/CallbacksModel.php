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

use CPCCore\Application\Exception\ApplicationCallbackException;

/**
 * Callbacks
 *
 * Acts as a container for callbacks registered in before and after route
 *
 * @package CPCCore
 * @subpackage Application
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class CallbacksModel
{

    /**
     * Callbacks
     * @var array
     */
    private $callbacks = [];

    /**
     * Execute Callbacks
     *
     * Run the callbacks in the order defined
     *
     * @throws ApplicationCallbackException
     */
    public function execute()
    {
        foreach ($this->callbacks as $callback) {
            if (false === call_user_func($callback)) {
                throw new ApplicationCallbackException('Route Callback function failed.');
            }
        }

        return true;
    }

    /**
     * Add a Callback
     *
     * @param mixed $callback
     * @throws ApplicationCallbackException
     */
    public function add($callback)
    {
        if (!is_callable($callback)) {
            throw new ApplicationCallbackException('Callback function cannot be found or is not callable.');
        }

        $this->callbacks[] = $callback;
        return true;
    }

}
