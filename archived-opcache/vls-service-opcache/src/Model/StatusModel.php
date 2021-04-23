<?php

/**
 * Opcache Service - Service for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache-service
 * @license    BSD 3-Clause
 */
namespace CPC\Service\Opcache\Model;

class StatusModel
{

    /**
     * Get details of the Opcache Memory Usage
     *
     * @return false|array
     */
    public function getMemoryUsage()
    {
        return $this->getConfigurationKey('memory_usage');
    }

    /**
     * Get details of the Opcache Cached Scripts
     *
     * @return false|array
     */
    public function getScripts()
    {
        return $this->getConfigurationKey('scripts', true);
    }

    /**
     * Get details of the Opcache Statistics
     *
     * @return false|array
     */
    public function getStatistics()
    {
        return $this->getConfigurationKey('opcache_statistics');
    }

    /**
     * Get details of the Opcache Statistics
     *
     * @return array
     */
    public function getStatus()
    {
        $status = opcache_get_status(false);

        unset($status['memory_usage']);
        unset($status['opcache_statistics']);

        return $status;
    }

    /**
     * Get the filtered Configuration data
     *
     * @param string $key
     * @param boolean $getScripts
     * @return false|array
     */
    private function getConfigurationKey($key, $getScripts = false)
    {
        $status = opcache_get_status($getScripts);

        if (array_key_exists($key, $status)) {
            return $status[$key];
        }

        return false;
    }
}
