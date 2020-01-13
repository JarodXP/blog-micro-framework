<?php

//Sets the autoload for composer packages

require __DIR__ .'/../vendor/autoload.php';

use App\Application;
use Keradus\Psr4Autoloader;

/*Autoloaders for classes, one per namespace*/

$coreLoader = new Psr4Autoloader();
$coreLoader->register();
$coreLoader->addNamespace('Core', __DIR__ . '/../src/Core');

$appLoader = new Psr4Autoloader();
$appLoader->register();
$appLoader->addNamespace('App', __DIR__ . '/../src/App');

$frontLoader = new Psr4Autoloader();
$frontLoader->register();
$frontLoader->addNamespace('Front', __DIR__ . '/../src/App/Front');

$adminLoader = new Psr4Autoloader();
$adminLoader->register();
$adminLoader->addNamespace('Admin', __DIR__ . '/../src/App/Admin');

$authenticationLoader = new Psr4Autoloader();
$authenticationLoader->register();
$authenticationLoader->addNamespace('Authentication', __DIR__ . '/../src/App/Authentication');

///GLOBALS VARIABLES//////////

//Sets a global variable for the config directory
$configDirectory = __DIR__ .'/../src/config';

//Sets a global variable for the routes parsed file
$routes = yaml_parse_file($configDirectory.'/routes.yml');

//Sets a global variable for the config parsed file
$config = yaml_parse_file($configDirectory.'/config.yml');

///RUN//////

$app = new Application(Application::DEVELOPMENT_STAGE);

$app->run();