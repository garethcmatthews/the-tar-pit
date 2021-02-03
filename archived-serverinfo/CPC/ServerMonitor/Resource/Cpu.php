<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Cpu implements iResource
{
    use Helper\Traits\Resource;

    /**
     * Get the CPU information
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server CPU Details',
            'data' => $this->getCpuData()
        );
    }

    /**
     * Extract the Element Data
     *
     * @param array $cpu
     * @return array
     */
    protected function extractElementData($cpu)
    {
        $results = array(
            'architecture' => false,
            'cpu_op_modes' => false,
            'cpus' => false,
            'threads_per_core' => false,
            'cores_per_socket' => false,
            'sockets' => false,
            'vendor_id' => false,
            'cpu_family' => false,
            'model' => false,
            'cpu_mhz' => false
        );

        $elements = explode("\n", $cpu);
        foreach ($elements as $element) {
            $key = $this->extractKey($element);
            if (key_exists($key, $results)) {
                $results[$key] = $this->extractData($element);
            }
        }

        return $results;
    }

    /**
     * Extract the Elements Data
     *
     * @param string $element
     * @return string
     */
    protected function extractData($element)
    {
        $data = trim(strrchr($element, ':'), ': ');
        return is_numeric($data) ? (int) $data : (string) $data;
    }

    /**
     * Extract the Elements Key
     *
     * @param string $element
     * @return string
     */
    protected function extractKey($element)
    {
        $element = strtolower($element);
        $element = strstr($element, ':', true);
        $element = str_replace(array(
            ' ',
            '-',
            '(',
            ')'
        ), array(
            '_',
            '_',
            '',
            ''
        ), strtolower($element));

        return $element;
    }

    /**
     * Get the CPU Data
     *
     * @throws Exception\ResourceException
     * @return array
     */
    protected function getCpuData()
    {
        if (! $cpu = shell_exec('lscpu')) {
            throw new Exception\ResourceException('Invalid CPU data returned by Server');
        }

        return $this->extractElementData($cpu);
    }
}
