<?php


namespace Admin;


use App\Application;
use Core\Controller;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentsController extends Controller
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

    public function commentsListAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/adminComments.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function editCommentAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/adminEditComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }

    }

    public function removeCommentAction()
    {
        $twig = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        try
        {
            echo $twig->render('/removeComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}