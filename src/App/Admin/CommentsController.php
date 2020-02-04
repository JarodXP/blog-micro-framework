<?php


namespace Admin;


use Core\Controller;
use Models\CommentManager;
use Services\ListPaginator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentsController extends Controller
{
    use ListPaginator;

    public function commentsListAction()
    {
        //Sets the options to be sent to the manager as parameter for the list
        $options = $this->paginatedListOptions();

        $commentManager = new CommentManager();

        //Gets the list of posts
        $this->templateVars['comments'] = $commentManager->findCommentsAndPost(null,$options);

        //Sets the variable to be sent to the twig template
        $this->paginatedListTwigVariables($options,'comments');

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