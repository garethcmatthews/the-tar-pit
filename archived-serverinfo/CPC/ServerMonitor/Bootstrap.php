<?php
namespace CPC\ServerMonitor;

use CPC\ServerMonitor\Core;

// Setup Error Reporting
$environment = getenv('APPLICATION_ENVIRONMENT') ?  : 'production';
if ($environment == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Set the Root Directory
chdir(realpath("../"));

// Setup Autoloader
spl_autoload_register(function ($classname)
{
    $classname = str_replace('\\', '/', $classname);
    $filename = getcwd() . '/' . $classname . '.php';
    require_once $filename;
});

// Run
try {
    $app = new Core\Application();
    $app->run('./CPC/ServerMonitor/Config/application.php');
} catch (\Exception $e) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "data" => array(
            "description" => 'Unexpected Application Error : ' . $e->getMessage()
        )
    ));
}
