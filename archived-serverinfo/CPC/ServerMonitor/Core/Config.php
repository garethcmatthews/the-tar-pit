<?php
namespace CPC\ServerMonitor\Core;

use CPC\ServerMonitor\Core\Exception;

/**
 * Server Monitor Application Configuration
 *
 * @author Gareth Matthews
 */
class Config
{

    /**
     * Enabled Resources
     *
     * @var array
     */
    private $resources = array();

    /**
     * Default Resources
     *
     * @var string
     */
    private $default = '';

    /**
     * Constructor
     *
     * @param array $config
     * @throws Exception\ConfiguratonFileException
     */
    public function __construct($config)
    {
        if (! is_array($config)) {
            throw new Exception\ConfiguratonFileException('Configation data is not a valid array');
        }
        $this->setResources($config);
        $this->setDefaulResource($config);
    }

    /**
     * Get the Default Resource
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Check if a module is enabled
     *
     * @param string $module
     * @return boolean
     */
    public function isEnabled($module)
    {
        return in_array($module, $this->resources);
    }

    /**
     * Parse and persist the Default
     *
     * @param array $config
     * @throws Exception\ConfiguratonFileException
     */
    private function setDefaulResource($config)
    {
        if (empty($config['default_resource'])) {
            throw new Exception\ConfiguratonFileException("'default_resource' has not been set.'");
        }
        if (! is_string($config['default_resource'])) {
            throw new Exception\ConfiguratonFileException("'default_resource' is not a valid string.");
        }
        if (! in_array($config['default_resource'], $config['enabled_resources'])) {
            throw new Exception\ConfiguratonFileException("'default_resource' is not an enabled resource.");
        }
        $this->default = $config['default_resource'];
    }

    /**
     * Parse and persist the Resources
     *
     * @param array $config
     * @throws Exception\ConfiguratonFileException
     */
    private function setResources($config)
    {
        if (empty($config['enabled_resources'])) {
            throw new Exception\ConfiguratonFileException('No Resources have been enabled');
        }
        if (! is_array($config['enabled_resources'])) {
            throw new Exception\ConfiguratonFileException("'enabled_resources' is not a valid array");
        }
        $this->resources = $config['enabled_resources'];
    }
}
