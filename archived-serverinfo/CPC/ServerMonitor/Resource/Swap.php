<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Swap implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get the Servers Swap File Usage
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Swap File Usage',
            'data' => $this->getSwapData()
        );
    }

    /**
     * Get Swap Data
     *
     * @return array
     */
    private function getSwapData()
    {
        $data = $this->getSwapInfo();
        return array(
            'total' => (int) $data[0],
            'used' => (int) $data[1],
            'free' => (int) $data[2]
        );
    }

    /**
     * Get Server Swap file usage
     *
     * @throws Exception\ResourceException
     * @return array
     */
    private function getSwapInfo()
    {
        if (! $swap = shell_exec('free -mb | grep Swap')) {
            throw new Exception\ResourceException('Invalid Swap data returned by Server');
        }
        return $this->extractSwapInfo($swap);
    }

    /**
     * Extract the Swap Information
     *
     * @param string $data
     * @throws Exception\ResourceException
     * @return array
     */
    private function extractSwapInfo($data)
    {
        $parts = explode(' ', preg_replace('/\s+/', ' ', $data));
        if (count($parts) < 4) {
            throw new Exception\ResourceException('Invalid Swap data returned by Server');
        }
        return array_slice($parts, 1, 3);
    }
}
