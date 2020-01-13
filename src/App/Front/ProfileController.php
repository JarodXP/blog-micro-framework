<?php


namespace Front;


use App\Application;
use Core\Controller;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfileController extends Controller
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        try
        {
            $this->setTwigLoader([__DIR__ . '/Views', __DIR__ . '/../Shared/Views']);
        }
        catch (LoaderError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function displayProfileAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/myProfile.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function displayContactFormAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/contact.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function sendContactFormAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/thankYou.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}