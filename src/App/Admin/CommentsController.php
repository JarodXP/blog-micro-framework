<?php


namespace Admin;


use Core\Controller;
use Entities\Comment;
use Exceptions\EntityAttributeException;
use Models\CommentManager;
use Services\ListPaginator;

class CommentsController extends Controller
{
    use ListPaginator;

    public const EDIT_ID = 'edit', REGISTER_ID = 'register';

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
        $commentManager = new CommentManager();

        //Creates instance from database
        $comment = $commentManager->findCommentsAndPost(['comments.id' => $this->httpParameters[self::EDIT_ID]])[0];

        //Sets the twig template vars to be sent to the twig environment for render
        $this->templateVars['comment'] = $comment;

        $this->templateVars[self::EDIT_ID] = $this->httpParameters[self::EDIT_ID];

        $this->twigRender('/adminEditComment.html.twig');

    }

    public function registerCommentAction()
    {
        $commentManager = new CommentManager();

        try
        {
            //Creates instance from database
            $comment = new Comment($commentManager->findCommentsAndPost(['comments.id' => $this->httpParameters[self::REGISTER_ID]])[0]);

            //Sets the status to 'false' if checkbox unchecked (as it doesn't sent any POST data)
            if(!isset($this->httpParameters['status']))
            {
                $this->httpParameters['status'] = false;
            }

            //And updates the properties with the httpParameters
            $comment->updateProperties($this->httpParameters);

            //Checks if all mandatory properties are set and not null
            $comment->isValid();

            //Updates the comment
            $commentManager->updateComment($comment);

            //Redirects to the comment
            $this->response->redirect('/admin/comments/?edit='.$comment->getId());
        }
        catch (EntityAttributeException $e)
        {
            //Redirects to the edit comment page
            $this->response->redirect('/admin/comments/?edit='.$this->httpParameters[self::REGISTER_ID],$e->getMessage());
        }
    }

    public function removeCommentAction()
    {
        $commentManager = new CommentManager();

        $commentManager->removeElement($this->httpParameters['remove']);

        $this->response->redirect('/admin/comments','Le commentaire a été supprimé');
    }
}