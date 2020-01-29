<?php


namespace Admin;


use Core\Controller;
use Models\PostManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
{
    protected array $table = [];

    public function postListAction()
    {
        $postManager = new PostManager();

        //Sets lists options to sort the table
        isset($this->httpParameters['limit']) ? $options['limit'] = $this->httpParameters['limit'] : $options['limit'] = 10;

        isset($this->httpParameters['page'])
            ? $options['offset'] = ($this->httpParameters['page']) * $options['limit'] - $options['limit']
            : $options['offset'] = null;

        if(isset($this->httpParameters['order']))
        {
            $options['order'] = $this->httpParameters['order'];
        }
        elseif (isset($this->httpParameters['currentOrder']))
        {
            $options['order'] = $this->httpParameters['currentOrder'];
        }
        else
        {
            $options['order'] = PostManager::DATE_ADDED;
        }

        if(isset($this->httpParameters['order'])
            && isset($this->httpParameters['currentOrder'])
            && isset($this->httpParameters['direction'])
            && $this->httpParameters['order'] == $this->httpParameters['currentOrder'])
        {
            $this->httpParameters['direction'] == 'asc'
                ? $options['direction'] = 'desc'
                : $options['direction'] = 'asc';
        }
        else
        {
            $options['direction'] = 'asc';
        }

        //Sets the list in the template variables
        $this->templateVars['posts'] = $postManager->findPostsAndUploads(null,$options);

        //Sets the page in the template variables
        isset($this->httpParameters['page'])
            ? $this->templateVars['page'] = $this->httpParameters['page']
            : $this->templateVars['page'] = '1';

        //Sets the nextPage in the template variables
        count($this->templateVars['posts']) >= $options['limit']
            ?  $this->templateVars['nextPage'] = $this->templateVars['page'] + 1
            :  $this->templateVars['nextPage'] = null;

        //Sets the prevPage in the template variables
        (is_null($options['offset']) || $options['offset'] == 0)
            ?  $this->templateVars['prevPage'] = null
            :  $this->templateVars['prevPage'] = $this->templateVars['page'] - 1;

        //Sets the direction in the template variables
        $this->templateVars['direction'] = $options['direction'];

        //Sets the order in the template variables
        $this->templateVars['order'] = $options['order'];

        //Renders the template
        $this->twigRender('adminPosts.html.twig',$this->templateVars);

    }

    public function editPostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminEditPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function newPostAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminNewPost.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
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