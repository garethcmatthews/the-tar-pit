<?php

use Zend\Loader\StandardAutoloader;

chdir(__DIR__ . '/../');

// Autoloader
require 'vendor/Zend/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader();
$loader->registerNamespace('Zend', 'vendor/Zend/');
$loader->registerNamespace('CPCCore', 'src/CPCCore');
$loader->register();
