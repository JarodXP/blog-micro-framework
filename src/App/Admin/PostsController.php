<?php


namespace Admin;


use Core\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
{
    public function postListAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminPosts.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function editPostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminEditPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function newPostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/newPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function removePostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/removePost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}