<?php

//Sets the autoload for composer packages

require __DIR__ .'/../vendor/autoload.php';

use Keradus\Psr4Autoloader;
use Core\Router;

/*Autoloaders for classes, one per namespace*/

$coreLoader = new Psr4Autoloader();
$coreLoader->register();
$coreLoader->addNamespace('Core', __DIR__ . '/../src/Core');

$configDirectory = __DIR__ .'/../src/config';

$routes = yaml_parse_file($configDirectory.'/routes.yml');

$router = new Router();