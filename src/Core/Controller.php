<?php


namespace Core;


use App\Application;
use ReflectionClass;
use ReflectionException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected Application $app;
    protected FilesystemLoader $twigLoader;
    protected Environment $twigEnvironment;

    public function __construct(Application $app)
    {
        $this->app = $app;

        //Defines the calling Controller "Views" directory path
        $viewsPath = $this->defineViewsDirectoryPath(static::class);

        //Sets the Twig loader
        $this->twigLoader = new FilesystemLoader([$viewsPath, __DIR__ . '/../App/Shared/Views']);

        //Sets the Twig environment
        $this->twigEnvironment = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        //Set globals variables to use on all templates
        $this->twigEnvironment->addGlobal('locale', $_ENV['locale']);
        $this->twigEnvironment->addGlobal('charset', $_ENV['charset']);
        $this->twigEnvironment->addGlobal('env', $_ENV['env']);
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
}