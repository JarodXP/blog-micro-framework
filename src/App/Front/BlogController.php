<?php


namespace Front;


use App\Application;
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

    public function __construct(Application $app, array $httpParameters)
    {
        parent::__construct($app, $httpParameters);

        //Sets the sidebar widget "last posts" list
        $this->sidebarPostsWidgetList(3);
    }

    public function postListAction()
    {
        //Sets the options to be sent to the manager as parameter for the list
        $options = $this->postsListOptions();

        $postManager = new PostManager();

        //Gets the list of posts
        $this->templateVars['posts'] = $postManager->findPostsAndUploads(['status' => Post::STATUS_PUBLISHED],$options);

        //Sets the list variables to be sent to the twig template (page number, next page...)
        $this->buildTemplateListVars($options);

        //Renders the template
        $this->twigRender('/frontBlog.html.twig');
    }

    public function displayPostAction()
    {
        $postManager = new PostManager();

        $this->templateVars['post'] = $postManager->findPostsAndUploads(['slug' => $this->httpParameters['postSlug']])[0];

        $this->twigRender('/frontPost.html.twig');
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