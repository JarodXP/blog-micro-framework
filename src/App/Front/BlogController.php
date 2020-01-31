<?php


namespace Front;


use Core\Controller;
use Entities\Post;
use Models\PostManager;
use Services\PostsListsBuilder;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController extends Controller
{
    use PostsListsBuilder;

    public function postListAction()
    {

        //Sets the options to be sent to the manager as parameter for the list
        $options = $this->listOptions();

        $postManager = new PostManager();

        //Gets the list of posts
        $this->templateVars['posts'] = $postManager->findPostsAndUploads(['status' => Post::STATUS_PUBLISHED],$options);

        //Sets the variable to be sent to the twig template
        $this->buildTemplateListVars($options);

        //Renders the template
        $this->twigRender('/frontBlog.html.twig');
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