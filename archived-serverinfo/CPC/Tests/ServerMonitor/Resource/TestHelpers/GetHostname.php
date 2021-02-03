<?php
namespace CPC\ServerMonitor\Resource
{

    function gethostname()
    {
        global $mockGetHostname;
        if (isset($mockGetHostname) && $mockGetHostname === true) {
            return false;
        } else {
            return call_user_func_array('gethostname', func_get_args());
        }
    }
}
