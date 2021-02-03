<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class Os implements iResource
{
    use Helper\Traits\Resource;

    /**
     * Get the Operating System information
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Linux Distro Information',
            'data' => $this->getOsData()
        );
    }

    /**
     * Get the OS Data
     *
     * @return array
     */
    private function getOsData()
    {
        $parts = $this->getOsInfo();
        return array(
            'operating_system' => $parts[0],
            'hostname' => $parts[1],
            'release' => $parts[2],
            'version' => $parts[3],
            'machine_type' => $parts[4]
        );
    }

    /**
     * Get the OS Info
     *
     * @return array
     */
    private function getOsInfo()
    {
        $distro = php_uname();
        return explode(' ', $distro);
    }
}
