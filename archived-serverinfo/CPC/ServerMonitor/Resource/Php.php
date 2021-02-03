<?php
namespace CPC\ServerMonitor\Resource;

use CPC\ServerMonitor\Helper;

class Php implements iResource
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
            'description' => 'PHP Version Information',
            'data' => $this->getVersionData()
        );
    }

    /**
     * Get Version Information
     *
     * @return array
     */
    private function getVersionData()
    {
        return array(
            'zend_version' => zend_version(),
            'version' => phpversion(),
            'extensions' => $this->getExtensions(),
            'zend_extensions' => $this->getExtensions(true)
        );
    }

    /**
     * Get the PHP Extensions
     *
     * @return array
     */
    private function getExtensions($zend = false)
    {
        $data = array();
        $extensions = get_loaded_extensions($zend);
        foreach ($extensions as $extension) {
            $data[$extension] = phpversion($extension);
        }

        return $data;
    }
}
