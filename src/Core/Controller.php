<?php


namespace Core;


use App\Application;
use ReflectionClass;
use ReflectionException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected Application $app;
    protected FilesystemLoader $twigLoader;
    protected Environment $twigEnvironment;
    protected ?array $httpParameters;
    protected HttpResponse $response;
    protected ?array $templateVars;

    public function __construct(Application $app, array $httpParameters)
    {
        $this->app = $app;

        //Defines the calling Controller "Views" directory path
        $viewsPath = $this->defineViewsDirectoryPath(static::class);

        //Sets the Twig loader
        $this->twigLoader = new FilesystemLoader([$viewsPath, __DIR__ . '/../App/Shared/Views']);

        //Sets the Twig environment
        $this->twigEnvironment = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        //Set globals variables to use on all templates
        $this->twigEnvironment->addGlobal('locale', $GLOBALS['locale']);
        $this->twigEnvironment->addGlobal('charset', $GLOBALS['charset']);
        $this->twigEnvironment->addGlobal('env', $GLOBALS['env']);
        $this->twigEnvironment->addGlobal('notification',$_SESSION['user']->getNotification());

        //Sets the Debug extension in development environment
        if($this->twigEnvironment->isDebug())
        {
            $this->twigEnvironment->addExtension(new DebugExtension());
        }

        //Sets the parameters to be used in the actions
        $this->httpParameters = $httpParameters;

        //Sets an instance of HttpResponse for the Controllers to send response errors
        $this->response = new HttpResponse();
    }

    /**
     * Defines the path of the Views directory for Twig Loader
     * Path depends on the controller class calling the constructor
     * @param string $controllerName
     * @return string
     */
    private function defineViewsDirectoryPath(string $controllerName):string
    {
        //Gets the complete filename (path and name) of the calling Controller
        try
        {
            $reflector = new ReflectionClass($controllerName);
        }
        catch (ReflectionException $e)
        {
            print_r($e->getMessage());
            exit();
        }

        $controllerFileName = $reflector->getFileName();

        //Removes the name of the Controller in the file name to defines the calling Controller directory path
        $controllerDirPath = str_replace($reflector->getShortName().'.php','',$controllerFileName);

        //Returns the Views directory path
        return $controllerDirPath.'Views';
    }

    /**
     * Centralized twig render
     * @param string $template
     * @param array $twigVars
     */
    protected function twigRender(string $template, array $twigVars = null):void
    {
        try
        {
            echo $this->twigEnvironment->render($template,$twigVars);

            exit();
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}