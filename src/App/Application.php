<?php
namespace App;


use Core\Router;
use InvalidArgumentException;

class Application
{
    protected int $stage;

    public const DEVELOPMENT_STAGE = 0,
        PRODUCTION_STAGE = 1;

    public function __construct(int $stage)
    {
        $this->setStage($stage);

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
     * Runs the app
     * @return void
     */
    public function run()
    {
        $router = new Router($_SERVER['REQUEST_URI']);

        //Instantiates the IndexController
        $controllerName = $router->getRoute()->getControllerName();

        $controller = new $controllerName();

        //Starts the action
        $actionName = $router->getRoute()->getActionName();

        $controller->$actionName();
    }
}