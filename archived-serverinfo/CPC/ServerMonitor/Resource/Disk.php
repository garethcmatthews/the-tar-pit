<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Disk implements iResource
{
    use Helper\Traits\Resource;

    /**
     * Get the Disk information
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server CPU Details',
            'data' => $this->getDiskData()
        );
    }

    /**
     * Extract Elements
     *
     * @param array $data
     * @throws Exception\ResourceException
     * @return array
     */
    private function extractDisks($data)
    {
        $disks = explode("\n", $data);
        if (count($disks) < 2) {
            throw new Exception\ResourceException('Invalid Disk data returned by Server - No Disk Data in result');
        }
        return $disks;
    }

    /**
     * Extract the Disk Element
     *
     * @param array $element
     * @throws Exception\ResourceException
     * @return array
     */
    private function extractDiskElement($element)
    {
        $element = explode(' ', preg_replace('!\s+!', ' ', $element));
        if (count($element) < 6) {
            throw new Exception\ResourceException('Invalid Disk data returned by Server - Too few Elements');
        }
        return $this->formatElement($element);
    }

    /**
     * Format the Element
     *
     * @param array $element
     * @return array
     */
    private function formatElement($element)
    {
        return array(
            'filesystem' => $element[0],
            'size' => $element[1],
            'used' => $element[2],
            'avail' => $element[3],
            'percentage_used' => $element[4],
            'mounted_on' => $element[5]
        );
    }

    /**
     * Get the Disk Data
     *
     * @return array
     */
    private function getDiskData()
    {
        $disks = $this->getDiskInfo();
        $results = array();
        $max = count($disks) - 1;
        for ($i = 1; $i < $max; $i ++) {
            $results[] = $this->extractDiskElement($disks[$i]);
        }

        return $results;
    }

    /**
     * Get the Disk Information
     *
     * @throws Exception\ResourceException
     * @return array
     */
    private function getDiskInfo()
    {
        if (! $data = shell_exec('df -Ph')) {
            throw new Exception\ResourceException('Invalid Disk data returned by Server - No Data Returned');
        }
        return $this->extractDisks($data);
    }
}
