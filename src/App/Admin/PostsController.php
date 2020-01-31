<?php


namespace Admin;


use Core\Controller;
use Entities\Post;
use Exceptions\EntityAttributeException;
use Models\PostManager;
use Services\FileUploader;
use Services\PostsListsBuilder;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
{
    use FileUploader,PostsListsBuilder;

    protected array $table = [];

    public const LIMIT = 'limit',
        PAGE = 'page',
        OFFSET = 'offset',
        ORDER = 'order',
        CURRENT_ORDER = 'currentOrder',
        DIRECTION = 'direction',
        DIRECTION_ASC = 'asc',
        POST_SLUG = 'postSlug',
        NEW_POST = 'new-post',
        DIRECTION_DESC = 'desc';

    public function postListAction()
    {
        //Sets the options to be sent to the manager as parameter for the list
        $options = $this->listOptions();

        $postManager = new PostManager();

        //Gets the list of posts
        $this->templateVars['posts'] = $postManager->findPostsAndUploads(null,$options);

        //Sets the variable to be sent to the twig template
        $this->buildTemplateListVars($options);

        //Renders the template
        $this->twigRender('adminPosts.html.twig');

    }

    public function editPostAction()
    {
        $postManager = new PostManager();

        //Creates instance from database
        $post = $postManager->findPostsAndUploads(['slug' => $this->httpParameters[self::POST_SLUG]])[0];

        //Sets the twig template vars to be sent to the twig environment for render
        $this->templateVars['post'] = $post;

        $this->templateVars[self::POST_SLUG] = $this->httpParameters[self::POST_SLUG];

        $this->twigRender('/adminEditPost.html.twig');
    }

    public function registerPostAction()
    {
        $postManager = new PostManager();

        try
        {
            //If it's a new post, creates a post instance from the httpParameters
            if($this->httpParameters[self::POST_SLUG] == self::NEW_POST)
            {
                $post = new Post($this->httpParameters);
            }
            //If it's an existing post, creates instance from database
            else
            {
                $post = new Post($postManager->findPostsAndUploads(['slug' => $this->httpParameters[self::POST_SLUG]])[0]);

                //And updates the properties with the httpParameters
                $post->updateProperties($this->httpParameters);
            }

            //Sets a variable to store the current postHeader id
            $currentPostHeaderId = null;

            //Checks if $_FILES['postHeaderFile'] contains a file
            if($_FILES['postHeaderFile']['error'] != 4)
            {
                //Uses FileUploader service to upload the post header image and get an Upload object
                $postHeader = $this->uploadImage('postHeaderFile',$this->httpParameters['alt']);

                //Stores the current post header id
                $currentPostHeaderId = $post->getHeaderId();

                //Sets the new postHeaderId property to the $post object
                $post->setHeaderId($postHeader->getId());
            }

            //Checks if all mandatory properties are set and not null
            $post->isValid();

            //Inserts or updates post depending on the "new-post" parameter
            if($this->httpParameters[self::POST_SLUG] == self::NEW_POST)
            {
                //Inserts the $post
                $postManager->insertPost($post);
            }

            else
            {
                //Updates the $post
                $postManager->updatePost($post);

                //Removes former postHeader (both in server and database)
                if(!is_null($currentPostHeaderId))
                {
                    $this->removeFile($currentPostHeaderId);
                }
            }

            //Redirects to the post
            $this->response->redirect('/admin/posts/'.$post->getSlug());

        }
        catch (EntityAttributeException $e)
        {
            //If a file has been created during process, removes it
            if(isset($postHeader) && !is_null($postHeader->getId()))
            {
                $this->removeFile($currentPostHeaderId);
            }

            //Redirects either to the edit post page or the new post page
            $this->response->redirect('/admin/posts/'.$this->httpParameters[self::POST_SLUG],$e->getMessage());
        }
    }

    public function displayNewPostAction()
    {
        //Sets the postSlug var to be sent to the twig environment for render
        $this->templateVars[self::POST_SLUG] = self::NEW_POST;

        $this->twigRender('/adminNewPost.html.twig');
    }

    public function removePostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminRemovePost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}