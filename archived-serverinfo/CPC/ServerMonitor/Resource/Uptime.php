<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Core\Exception;
use CPC\ServerMonitor\Helper;

class Uptime implements iResource
{

    use Helper\Traits\Resource;

    /**
     * Get the Server Uptime Data
     *
     * @return string
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Uptime and Idle Time',
            'data' => $this->getUptimeData()
        );
    }

    /**
     * Get the Uptime Data
     *
     * @return array
     */
    private function getUptimeData()
    {
        $cores = $this->getNumberOfCpuCores();
        $result = $this->getTimeInfo();
        $upTime = $this->extractUptime($result);
        $idleTime = $this->extractIdleTime($result);
        $idleTimePerCore = $idleTime / $cores;

        return array(
            'cpu_cores' => $cores,
            'uptime' => $this->formatTime($upTime),
            'idletime_total' => $this->formatTime($idleTime),
            'idletime_per_core' => $this->formatTime($idleTimePerCore)
        );
    }

    /**
     * Get the Number of CPU Cores
     *
     * @throws Exception\ResourceException
     * @return number
     */
    private function getNumberOfCpuCores()
    {
        if (! $cores = shell_exec('nproc')) {
            throw new Exception\ResourceException('Invalid nproc data returned by Server');
        }
        return (int) $cores;
    }

    /**
     * Get the Uptime Data
     *
     * @throws Exception\ResourceException
     * @return string
     */
    private function getTimeInfo()
    {
        if (! $result = shell_exec('cat /proc/uptime')) {
            throw new Exception\ResourceException('Invalid upTime data returned by Server');
        }
        return trim($result);
    }

    /**
     * Extract Idle Time
     *
     * @param int $time
     * @return string
     */
    private function extractIdletime($time)
    {
        return trim(strrchr($time, ' '));
    }

    /**
     * Extract Uptime
     *
     * @param int $time
     * @return string
     */
    private function extractUptime($time)
    {
        return strstr($time, ' ', true);
    }

    /**
     * Format Time
     */
    private function formatTime($time)
    {
        $totalMinutes = $time / 60;
        $totalHours = $time / 3600;

        $days = floor($time / 86400);
        $hours = floor($totalHours - ($days * 24));
        $minutes = floor($totalMinutes - ($days * 60 * 24) - ($hours * 60));

        return array(
            'time' => (int) floor($time),
            'days' => (int) $days,
            'hours' => (int) $hours,
            'minutes' => (int) $minutes
        );
    }
}
