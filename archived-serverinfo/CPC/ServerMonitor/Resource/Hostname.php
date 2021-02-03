<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Hostname implements iResource
{
    use Helper\Traits\Resource;

    /**
     * Get the Server Hostname
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Servers Hostname',
            'data' => $this->getHostnameData()
        );
    }

    /**
     * Get the Hostname
     *
     * @throws Exception\ResourceException
     * @return string
     */
    private function getHostname()
    {
        if (! $hostname = gethostname()) {
            throw new Exception\ResourceException('Invalid Hostname data returned by Server');
        }

        return $hostname;
    }

    /**
     * Get the Hostname Data
     *
     * @return array
     */
    private function getHostnameData()
    {
        return array(
            'hostname' => $this->getHostname()
        );
    }
}
