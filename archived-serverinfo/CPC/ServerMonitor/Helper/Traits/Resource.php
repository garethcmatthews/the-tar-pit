<?php
namespace CPC\ServerMonitor\Helper\Traits;

trait Resource
{

    /**
     * Extract the Resource Name
     *
     * @return string
     */
    protected function getResourceName()
    {
        // todo throw exception on empty!!
        return ltrim(strrchr(__CLASS__, '\\'), '\\');
    }
}