<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Memory implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get the Servers Memory Usage
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Memory Usage',
            'data' => $this->getMemoryData()
        );
    }

    private function getMemoryData()
    {
        $memory = $this->getMemoryInfo();
        $total = $this->extractValue($memory, 'MemTotal');
        $free = $this->extractValue($memory, 'MemFree');
        $used = $total - $free;

        return array(
            'total' => $total,
            'free' => $free,
            'used' => $used
        );
    }

    /**
     * Extract Data
     *
     * @param string $label
     * @return int|boolean
     */
    private function extractValue($data, $label)
    {
        if (preg_match("/{$label}:\s+(\d+)/", $data, $pieces)) {
            return (int) $pieces[1];
        }
        return false;
    }

    /**
     * Get Server Memory usage
     *
     * @throws Exception\ResourceException
     * @return string
     */
    private function getMemoryInfo()
    {
        if (! $info = shell_exec('cat /proc/meminfo')) {
            throw new Exception\ResourceException('Invalid Memory data returned by Server');
        }

        return $info;
    }
}
