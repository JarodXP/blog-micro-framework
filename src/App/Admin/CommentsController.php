<?php


namespace Admin;


use Core\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentsController extends Controller
{
    public function commentsListAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminComments.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function editCommentAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminEditComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }

    }

    public function removeCommentAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/removeComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}