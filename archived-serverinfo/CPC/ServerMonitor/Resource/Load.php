<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class Load implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get the Servers Load Average
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Load Average',
            'data' => $this->getLoadData()
        );
    }

    /**
     * Get the Load Data
     *
     * @return array
     */
    private function getLoadData()
    {
        $load = sys_getloadavg();
        return array(
            'last_one_minute' => $load[0],
            'last_five_minutes' => $load[1],
            'last_fifteen_minutes' => $load[2]
        );
    }
}
