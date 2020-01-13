<?php


namespace Core;


use App\Application;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected Application $app;
    protected FilesystemLoader $twigLoader;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Sets the Twig Loader
     * @param array $templatesDirectories
     * @throws LoaderError
     */
    protected function setTwigLoader(array $templatesDirectories):void
    {
        //Instanciates Twig Loader
        $this->twigLoader = new FilesystemLoader($templatesDirectories[0]);

        //Add other paths if specified
        for($i = 1; $i < count($templatesDirectories); $i++)
        {
            $this->twigLoader->addPath($templatesDirectories[$i]);
        }
    }
}