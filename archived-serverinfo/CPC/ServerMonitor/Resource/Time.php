<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class Time implements iResource
{
    use Helper\Traits\Resource;

    /**
     * Get the Server Time Data
     *
     * @return array
     */
    public function getData()
    {
        return array(
            'status' => self::statusOkay,
            'resource' => $this->getResourceName(),
            'description' => 'Server Time',
            'data' => $this->getTimeData()
        );
    }

    /**
     * Get the Time Data
     *
     * @return array
     */
    private function getTimeData()
    {
        $info = getdate();
        return array(
            'timezone' => date_default_timezone_get(),
            'epoch' => $info[0],
            'year' => $info['year'],
            'month' => $info['month'],
            'month_number' => $info['mon'],
            'day_of_year' => $info['yday'],
            'day_of_month' => $info['mday'],
            'day_of_week_number' => $info['wday'],
            'day_of_week' => $info['weekday'],
            'hours' => $info['hours'],
            'minutes' => $info['minutes'],
            'seconds' => $info['seconds']
        );
    }
}
