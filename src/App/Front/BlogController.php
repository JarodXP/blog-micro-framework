<?php


namespace Front;


use App\Application;
use Core\Controller;
use Core\HttpResponse;
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

        //Gets the list of posts corresponding to the slug
        $postData = $postManager->findPostsAndUploads(['slug' => $this->httpParameters['postSlug']]);

        //If not empty, sends the post to the template vars
        if(!empty($postData))
        {
            $this->templateVars['post'] = $postData[0];
        }
        else
        {
            $this->response->redirect('/not-found',HttpResponse::NOT_FOUND);
        }

        //Displays the page
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

    public function notFoundAction()
    {
        $this->twigRender('404.html.twig');
    }
}