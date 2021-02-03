<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class About implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get the Application Verion Data
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'API version information',
            'data' => $this->getVersionData()
        );
    }

    /**
     * Get the Version Data
     *
     * @return array
     */
    private function getVersionData()
    {
        return array(
            'title' => 'Server Monitor API',
            'version' => 0.1
        );
    }
}
