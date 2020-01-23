<?php
namespace App;


use Core\Router;
use InvalidArgumentException;

class Application
{
    protected string $env;
    protected array $environmentOptions;

    public const DEVELOPMENT_ENV = "dev",
        PRODUCTION_ENV = "prod";

    public function __construct(string $env)
    {
        $this->setEnv($env);

        $this->setEnvironmentOptions();
    }

    /**
     * Gets the env of the app (Development or Production)
     * @return string
     */
    public function getEnv():string
    {
        return $this->env;
    }

    /**
     * Sets the Twig Environment options depending on the app env
     * @return array
     */
    public function getEnvironmentOptions(): array
    {
        return $this->environmentOptions;
    }

    /**
     * Sets the env of the app (Development or Production)
     * @param string $env
     * @return void
     */
    protected function setEnv(string $env = self::DEVELOPMENT_ENV): void
    {
        //Checks if the env is valid
        if(!($env == self::DEVELOPMENT_ENV || $env == self::PRODUCTION_ENV))
        {
            throw new InvalidArgumentException('The application $env argument should be one of the 
            following constant : DEVELOPMENT_ENV or PRODUCTION_ENV');
        }
        $this->env = $env;
    }

    /**
     * Sets the Twig Environment options depending on the app env
     */
    protected function setEnvironmentOptions():void
    {
        //Checks env status and sets cache and debug attributes for instance of Twig Environment
        if($this->env == self::PRODUCTION_ENV)
        {
            $twigEnvironmentOptions = [
                'cache' => $GLOBALS['cacheDirectory'],
                'debug' => false,
                'charset' => $GLOBALS['charset']
            ];
        }

        else
        {
            $twigEnvironmentOptions = [
                'cache' => false,
                'debug' => true,
                'charset' => $GLOBALS['charset']
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
        //Closes the notification if requested
        $uri = $this->closeNotification();

        $router = new Router($uri);

        //Instantiates the right controller with the name got from the route
        $controllerName = $router->getRoute()->getControllerName();

        $controller = new $controllerName($this,$router->getHttpParameters());

        //Starts the right action with the name got from the route
        $actionName = $router->getRoute()->getActionName();

        $controller->$actionName();
    }

    /**
     * Changes the $_SESSION['user'] notification
     * @return string
     */
    private function closeNotification():string
    {
        $uri = $_SERVER['REQUEST_URI'];

        //Checks if the GET notif parameter is set to 'close'
        if(isset($_GET['notif']) && $_GET['notif'] = 'close')
        {
            //Modifies $_SESSION['user'] notification
            $_SESSION['user']->setNotification('');

            //Removes the parameter from the uri
            $uri = str_replace('?notif=close','',$uri);
        }

        return $uri;
    }
}