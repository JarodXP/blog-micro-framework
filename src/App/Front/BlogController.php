<?php


namespace Front;


use Core\Controller;
use Models\UserManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController extends Controller
{
    public function postListAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/frontBlog.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function displayPostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/frontPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function sendCommentAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/frontThankYouComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}