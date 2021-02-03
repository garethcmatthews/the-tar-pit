<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class Server implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get Details of the Web Server
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Web Server information',
            'data' => $this->getServerData()
        );
    }

    /**
     * Get the Version Data
     *
     * @return array
     */
    private function getServerData()
    {
        return array(
            'software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'unknown',
            'name' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'unknown',
            'address' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'unknown',
            'port' => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'unknown'
        );
    }
}
