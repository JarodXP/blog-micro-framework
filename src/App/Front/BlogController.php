<?php


namespace Front;


use App\Application;
use Core\Controller;
use Core\HttpResponse;
use Entities\Comment;
use Entities\Post;
use Exceptions\EntityAttributeException;
use Exceptions\MailException;
use Models\CommentManager;
use Models\PostManager;
use Services\ListPaginator;
use Services\MailHandler;
use Services\SidebarBuilder;

class BlogController extends Controller
{
    use ListPaginator,SidebarBuilder;

    public function __construct(Application $app, array $httpParameters)
    {
        parent::__construct($app, $httpParameters);

        //Sets the sidebar widget "last posts" list
        $this->sidebarPostsWidgetList(3);

        //Sets the sidebar widget "networks" list
        $this->sidebarNetworksList();

        //Sets the sidebar widget "resume"
        $this->sidebarResume();
    }

    public function postListAction()
    {
        //Sets the options to be sent to the manager as parameter for the list
        $options = $this->paginatedListOptions();

        $postManager = new PostManager();

        //Gets the list of posts
        $this->templateVars['posts'] = $postManager->findPostsAndHeaders(['status' => Post::STATUS_PUBLISHED],$options);

        //Sets the list variables to be sent to the twig template (page number, next page...)
        $this->paginatedListTwigVariables($options,'posts');

        //Renders the template
        $this->twigRender('/frontBlog.html.twig');
    }

    public function displayPostAction()
    {
        $postManager = new PostManager();

        //Gets the list of posts corresponding to the slug
        $postData = $postManager->findPostsAndHeaders(['slug' => $this->httpParameters['postSlug']]);

        if(!empty($postData))
        {
            //If not empty, sends the post to the template
            $this->templateVars['post'] = $postData[0];

            //Gets the list of comments corresponding to the post id and sends it to the template
            $commentManager = new CommentManager();

            $this->templateVars['comments'] = $commentManager->findListBy([
                CommentManager::POST_ID => $postData[0]['id'],
                CommentManager::STATUS => Comment::STATUS_PUBLISHED
            ]);
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
        //Sets a variable for previous page uri
        $_SESSION[Controller::PREVIOUS_URI] = '/blog/'.$this->httpParameters['postSlug'];

        try
        {
            $comment = new Comment($this->httpParameters);

            //checks if all properties are set
            $comment->isValid();

            //inserts in the database
            $commentManager = new CommentManager();

            $commentManager->insertComment($comment);

            //Gets the post title for the mail
            $postManager = new PostManager();

            //Adds the post title to the httpParameters
            $this->httpParameters['postTitle'] = $postManager->findOneBy(['id' => $this->httpParameters['postId']])['title'];

            $mailHandler = new MailHandler($this->httpParameters);

            $mailHandler->sendMail(MailHandler::COMMENT_MAIL);
        }
        catch(EntityAttributeException | MailException $e)
        {
            //Redirects to post
            $this->response->redirect($_SESSION[Controller::PREVIOUS_URI],$e->getMessage());
        }

        $this->response->redirect('/blog/thank-you');
    }

    public function thankYouAction()
    {
        $this->templateVars['h1'] = 'Merci pour votre commentaire!';

        $this->templateVars['content'] = 'Votre commentaire a bien été envoyé, 
        je m\'efforce de le modérer dans les plus brefs délais.';

        //Sets the link for "back" button
        $this->templateVars['previousPage'] = $_SESSION[Controller::PREVIOUS_URI];

        $this->twigRender('/frontThankYou.html.twig');
    }

    public function notFoundAction()
    {
        $this->twigRender('404.html.twig');
    }
}