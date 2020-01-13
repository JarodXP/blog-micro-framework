<?php


namespace Admin;


use App\Application;
use Core\Controller;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
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
        }
    }

    public function postListAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/adminPosts.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function editPostAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/adminEditPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function newPostAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/newPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function removePostAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/removePost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}