<?php
namespace CPC\ServerMonitor\Resource
{

    function shell_exec()
    {
        global $mockShellExec;
        if (isset($mockShellExec) && $mockShellExec === true) {
            return null;
        } else {
            return call_user_func_array('shell_exec', func_get_args());
        }
    }
}
