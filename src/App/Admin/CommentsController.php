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

        $this->twigRender('/adminComments.html.twig');
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
            echo $this->twigEnvironment->render('/adminRemoveComment.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}