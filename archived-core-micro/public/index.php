<?php

use \CPCCore\Application\ApplicationService;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../src/Bootstrap.php';
$app = new ApplicationService();

$app->route('root')
        ->setRoute('/')
        ->setController(function() {
            echo "Root Page";
        });

$app->route('home')
        ->setRoute('/home')
        ->setController(function() {
            echo "Home Page";
        });

$app->route('hello-world')
        ->setRoute('/hello/@name')
        ->setController(function($name) {
            echo "Hello World - $name";
        });

$app->run();
