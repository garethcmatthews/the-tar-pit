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

class ConfigurationModel
{

    /**
     * Get details of the Opcache Blacklist
     *
     * @return array|false
     */
    public function getBlacklist()
    {
        return $this->getConfigurationKey('blacklist');
    }

    /**
     * Get details of the Opcache Directives/Configuration
     *
     * @return false|array
     */
    public function getDirectives()
    {
        return $this->getConfigurationKey('directives');
    }

    /**
     * Get details of the Opcache Version
     *
     * @return false|array
     */
    public function getVersion()
    {
        return $this->getConfigurationKey('version');
    }

    /**
     * Get the filtered Configuration data
     *
     * @param string $key
     * @return false|array
     */
    private function getConfigurationKey($key)
    {
        $configuration = opcache_get_configuration();

        if (array_key_exists($key, $configuration)) {
            return $configuration[$key];
        }

        return false;
    }
}
