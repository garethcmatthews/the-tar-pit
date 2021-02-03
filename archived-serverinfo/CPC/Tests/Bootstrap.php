<?php
namespace CPC\ServerMonitor;

// Setup Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the Root Directory
define('APPLICATION_PATH', '/home/dev/www/ServerMonitor/');

// Setup Autoloader
spl_autoload_register(function ($classname)
{
    $classname = str_replace('\\', '/', $classname);
    $filename = APPLICATION_PATH . $classname . '.php';
    require_once $filename;
});
