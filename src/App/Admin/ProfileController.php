<?php


namespace Admin;


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

    public function editProfileAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/adminMyProfile.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }    }


}