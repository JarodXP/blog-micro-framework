<?php


namespace Admin;


use Core\Controller;
use Entities\Post;
use Exceptions\EntityAttributeException;
use Models\PostManager;
use Services\FileUploader;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
{
    use FileUploader;

    protected array $table = [];

    public const LIMIT = 'limit',
        PAGE = 'page',
        OFFSET = 'offset',
        ORDER = 'order',
        CURRENT_ORDER = 'currentOrder',
        DIRECTION = 'direction',
        DIRECTION_ASC = 'asc',
        DIRECTION_DESC = 'desc';

    public function postListAction()
    {
        $postManager = new PostManager();

        //Sets lists options to sort the table
        isset($this->httpParameters[self::LIMIT]) ? $options[self::LIMIT] = $this->httpParameters[self::LIMIT] : $options[self::LIMIT] = 10;

        isset($this->httpParameters[self::PAGE])
            ? $options[self::OFFSET] = ($this->httpParameters[self::PAGE]) * $options[self::LIMIT] - $options[self::LIMIT]
            : $options[self::OFFSET] = null;

        if(isset($this->httpParameters[self::ORDER]))
        {
            $options[self::ORDER] = $this->httpParameters[self::ORDER];
        }
        elseif (isset($this->httpParameters[self::CURRENT_ORDER]))
        {
            $options[self::ORDER] = $this->httpParameters[self::CURRENT_ORDER];
        }
        else
        {
            $options[self::ORDER] = PostManager::DATE_ADDED;
        }

        if(isset($this->httpParameters[self::ORDER])
            && isset($this->httpParameters[self::CURRENT_ORDER])
            && isset($this->httpParameters[self::DIRECTION])
            && $this->httpParameters[self::ORDER] == $this->httpParameters[self::CURRENT_ORDER])
        {
            $this->httpParameters[self::DIRECTION] == self::DIRECTION_ASC
                ? $options[self::DIRECTION] = self::DIRECTION_DESC
                : $options[self::DIRECTION] = self::DIRECTION_ASC;
        }
        else
        {
            $options[self::DIRECTION] = self::DIRECTION_ASC;
        }

        //Sets the list in the template variables
        $this->templateVars['posts'] = $postManager->findPostsAndUploads(null,$options);

        //Sets the page in the template variables
        isset($this->httpParameters[self::PAGE])
            ? $this->templateVars[self::PAGE] = $this->httpParameters[self::PAGE]
            : $this->templateVars[self::PAGE] = '1';

        //Sets the nextPage in the template variables
        count($this->templateVars['posts']) >= $options[self::LIMIT]
            ?  $this->templateVars['nextPage'] = $this->templateVars[self::PAGE] + 1
            :  $this->templateVars['nextPage'] = null;

        //Sets the prevPage in the template variables
        (is_null($options[self::OFFSET]) || $options[self::OFFSET] == 0)
            ?  $this->templateVars['prevPage'] = null
            :  $this->templateVars['prevPage'] = $this->templateVars[self::PAGE] - 1;

        //Sets the direction in the template variables
        $this->templateVars[self::DIRECTION] = $options[self::DIRECTION];

        //Sets the order in the template variables
        $this->templateVars[self::ORDER] = $options[self::ORDER];

        //Renders the template
        $this->twigRender('adminPosts.html.twig');

    }

    public function editPostAction()
    {
        $post = new Post();

        $this->templateVars['formAction'] = 'post-'.$post->getSlug();

        $this->twigRender('/adminEditPost.html.twig');
    }

    public function registerNewPostAction()
    {
        $post = new Post($this->httpParameters);

        $postManager = new PostManager();

        //Sets a variable to store the current postHeader id
        $currentPostHeaderId = null;

        try
        {
            //Checks if $_FILES['postHeaderFile'] contains a file
            if(($_FILES['postHeaderFile']['error'] != 4))
            {
                //Uses FileUploader service to upload the post header image and get an Upload object
                $postHeader = $this->uploadImage('postHeaderFile',$this->httpParameters['alt']);

                //Stores the current $admin avatarId
                $currentPostHeaderId = $post->getHeaderId();

                //Sets the new avatarId property to the $admin object
                $post->setHeaderId($postHeader->getId());
            }

            //Checks if all mandatory properties are set and not null
            if($post->isValid())
            {
                //Inserts the $post
                $postManager->insertPost($post);

                //Removes former postHeader (both in server and database)
                if(!is_null($currentPostHeaderId))
                {
                    $this->removeFile($currentPostHeaderId);
                }
            }

        }
        catch (EntityAttributeException $e)
        {
            $this->response->redirect('/admin/posts/new-post',$e->getMessage());
        }
    }

    public function displayNewPostAction()
    {
        $this->templateVars['formAction'] = 'register-new-post';

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