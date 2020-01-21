<?php

//Sets the autoload for composer packages

declare(strict_types=1);

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

$entitiesLoader = new Psr4Autoloader();
$entitiesLoader->register();
$entitiesLoader->addNamespace('Entities', __DIR__ . '/../src/Entities');

$modelsLoader = new Psr4Autoloader();
$modelsLoader->register();
$modelsLoader->addNamespace('Models', __DIR__ . '/../src/Models');

$serviceLoader = new Psr4Autoloader();
$serviceLoader->register();
$serviceLoader->addNamespace('Services', __DIR__ . '/../src/Services');

///GLOBALS VARIABLES//////////
$cacheDirectory = __DIR__ . '/../var/cache/';
$configDirectory = __DIR__ . '/../config';

//Sets a global variable for the routes parsed file
$routes = yaml_parse_file($configDirectory.'/routes.yml');

//Sets a global variable for the config parsed file
$config = yaml_parse_file($configDirectory.'/config.yml');

//Global environment variables for Twig Views
$locale = $config['APP_LOCALE'];
$charset = $config['APP_CHARSET'];
$env = $config['APP_ENV'];

//Sends the application environment for the Twig Environment instance
$app = new Application($env);

///RUN//////
$app->run();