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

        //Sets lists options
        isset($this->httpParameters['limit']) ? $options['limit'] = $this->httpParameters['limit'] : $options['limit'] = 10;

        isset($this->httpParameters['page'])
            ? $options['offset'] = ($this->httpParameters['page']-1) * $options['limit']
            : $options['offset'] = null;

        isset($this->httpParameters['order'])
            ? $options['order'] = $this->httpParameters['order']
            : $options['order'] = PostManager::DATE_ADDED;

        isset($this->httpParameters['direction'])
            ? $options['direction'] = $this->httpParameters['direction']
            : $options['direction'] = 'asc';

        $this->templateVars['posts'] = $postManager->findPostsAndUploads(null,$options);

        isset($this->httpParameters['page'])
            ? $page = $this->httpParameters['page']
            : $page = '1';

        $this->templateVars['page'] = $page;

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