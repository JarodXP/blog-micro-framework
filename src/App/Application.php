<?php
namespace App;


use Core\Router;
use InvalidArgumentException;

class Application
{
    protected int $stage;
    protected array $environmentOptions;

    public const DEVELOPMENT_STAGE = 0,
        PRODUCTION_STAGE = 1;

    public function __construct(int $stage)
    {
        $this->setStage($stage);

        $this->setEnvironmentOptions();

    }

    /**
     * Gets the stage of the app (Development or Production)
     * @return int
     */
    public function getStage():int
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
     * @param int $stage
     * @return void
     */
    protected function setStage(int $stage = self::DEVELOPMENT_STAGE): void
    {
        //Checks if the stage is valid
        if(!isset($stage) || !($stage == self::DEVELOPMENT_STAGE || $stage == self::PRODUCTION_STAGE))
        {
            throw new InvalidArgumentException('The application $stage argument should be one of the 
            following constant : DEVELOPMENT_STAGE or PRODUCTION STAGE');
        }
        $this->stage = $stage;
    }

    /**
     * Sets the Twig Environment options depending on the app stage
     */
    protected function setEnvironmentOptions():void
    {
        //Checks stage status and sets cache and debug attributes for instance of Twig Environment
        if($this->stage == self::PRODUCTION_STAGE)
        {
            $twigEnvironmentOptions = [
                'cache' => $_ENV['cacheDirectory'],
                'debug' => false
            ];
        }

        else
        {
            $twigEnvironmentOptions = [
                'cache' => false,
                'debug' => true
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