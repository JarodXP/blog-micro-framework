<?php
namespace App;


use Core\Router;
use InvalidArgumentException;

class Application
{
    protected string $stage;
    protected array $environmentOptions;

    public const DEVELOPMENT_ENV = "dev",
        PRODUCTION_ENV = "prod";

    public function __construct(string $stage)
    {
        $this->setStage($stage);

        $this->setEnvironmentOptions();
    }

    /**
     * Gets the stage of the app (Development or Production)
     * @return string
     */
    public function getStage():string
    {
        return $this->stage;
    }

    /**
     * Sets the Twig Environment options depending on the app stage
     * @return array
     */
    public function getEnvironmentOptions(): array
    {
        return $this->environmentOptions;
    }

    /**
     * Sets the stage of the app (Development or Production)
     * @param string $stage
     * @return void
     */
    protected function setStage(string $stage = self::DEVELOPMENT_ENV): void
    {
        //Checks if the stage is valid
        if(!($stage == self::DEVELOPMENT_ENV || $stage == self::PRODUCTION_ENV))
        {
            throw new InvalidArgumentException('The application $stage argument should be one of the 
            following constant : DEVELOPMENT_ENV or PRODUCTION_ENV');
        }
        $this->stage = $stage;
    }

    /**
     * Sets the Twig Environment options depending on the app stage
     */
    protected function setEnvironmentOptions():void
    {
        //Checks stage status and sets cache and debug attributes for instance of Twig Environment
        if($this->stage == self::PRODUCTION_ENV)
        {
            $twigEnvironmentOptions = [
                'cache' => $_ENV['cacheDirectory'],
                'debug' => false,
                'charset' => $_ENV['charset']
            ];
        }

        else
        {
            $twigEnvironmentOptions = [
                'cache' => false,
                'debug' => true,
                'charset' => $_ENV['charset']
            ];
        }

        $this->environmentOptions = $twigEnvironmentOptions;
    }

    /**
     * Runs the app
     * @return void
     */
    public function run()
    {
        $router = new Router($_SERVER['REQUEST_URI']);

        //Instantiates the right controller with the name got from the route
        $controllerName = $router->getRoute()->getControllerName();

        $controller = new $controllerName($this);

        //Starts the right action with the name got from the route
        $actionName = $router->getRoute()->getActionName();

        $controller->$actionName();
    }
}