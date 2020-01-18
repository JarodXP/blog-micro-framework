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

$exceptionLoader = new Psr4Autoloader();
$exceptionLoader->register();
$exceptionLoader->addNamespace('Exceptions', __DIR__ . '/../src/Exceptions');

///ENVIRONMENT VARIABLES///////
$_ENV['cacheDirectory'] = __DIR__ .'/../cache/';
$_ENV['configDirectory'] = __DIR__ . '/../config';


///GLOBALS VARIABLES//////////

//Sets a global variable for the routes parsed file
$routes = yaml_parse_file($_ENV['configDirectory'].'/routes.yml');

//Sets a global variable for the config parsed file
$config = yaml_parse_file($_ENV['configDirectory'].'/config.yml');

//Enviroment variables for Twig Views
$_ENV['locale'] = $config['APP_LOCALE'];
$_ENV['charset'] = $config['APP_CHARSET'];

//Gets the application env to set Twig environment
$_ENV['env'] = $config['APP_ENV'];

$app = new Application($_ENV['env']);

///RUN//////
$app->run();