<?php


namespace Services;


use Admin\PostsController;
use Models\PostManager;

trait PostsListsBuilder
{
    protected ?array $httpParameters;

    protected ?array $templateVars;

    /**
     * Sets the list options for the posts lists.
     * Used in admin posts and blogs
     * @return array
     */
    public function listOptions():array
    {
        //Sets lists options limit
        isset($this->httpParameters[PostsController::LIMIT])
            ? $options[PostsController::LIMIT] = $this->httpParameters[PostsController::LIMIT]
            : $options[PostsController::LIMIT] = 10;

        //Sets lists options offset
        isset($this->httpParameters[PostsController::PAGE])
            ? $options[PostsController::OFFSET] = ($this->httpParameters[PostsController::PAGE]) * $options[PostsController::LIMIT] - $options[PostsController::LIMIT]
            : $options[PostsController::OFFSET] = null;

        //Sets lists options order
        if(isset($this->httpParameters[PostsController::ORDER]))
        {
            $options[PostsController::ORDER] = $this->httpParameters[PostsController::ORDER];
        }
        elseif (isset($this->httpParameters[PostsController::CURRENT_ORDER]))
        {
            $options[PostsController::ORDER] = $this->httpParameters[PostsController::CURRENT_ORDER];
        }
        else
        {
            $options[PostsController::ORDER] = PostManager::DATE_ADDED;
        }

        //Sets lists options direction
        if(isset($this->httpParameters[PostsController::ORDER])
            && isset($this->httpParameters[PostsController::CURRENT_ORDER])
            && isset($this->httpParameters[PostsController::DIRECTION])
            && $this->httpParameters[PostsController::ORDER] == $this->httpParameters[PostsController::CURRENT_ORDER])
        {
            $this->httpParameters[PostsController::DIRECTION] == PostsController::DIRECTION_ASC
                ? $options[PostsController::DIRECTION] = PostsController::DIRECTION_DESC
                : $options[PostsController::DIRECTION] = PostsController::DIRECTION_ASC;
        }
        else
        {
            $options[PostsController::DIRECTION] = PostsController::DIRECTION_ASC;
        }

        return $options;
    }

    /**
     * Sets the variables related to the list to be sent to the twig template
     * @param array $options
     */
    public function buildTemplateListVars(array $options):void
    {
        //Sets the limit in the template variables
        $this->templateVars[PostsController::LIMIT] = $options[PostsController::LIMIT];

        //Sets the page in the template variables
        isset($this->httpParameters[PostsController::PAGE])
            ? $this->templateVars[PostsController::PAGE] = $this->httpParameters[PostsController::PAGE]
            : $this->templateVars[PostsController::PAGE] = '1';

        //Sets the nextPage in the template variables
        count($this->templateVars['posts']) >= $options[PostsController::LIMIT]
            ?  $this->templateVars['nextPage'] = $this->templateVars[PostsController::PAGE] + 1
            :  $this->templateVars['nextPage'] = null;

        //Sets the prevPage in the template variables
        (is_null($options[PostsController::OFFSET]) || $options[PostsController::OFFSET] == 0)
            ?  $this->templateVars['prevPage'] = null
            :  $this->templateVars['prevPage'] = $this->templateVars[PostsController::PAGE] - 1;

        //Sets the direction in the template variables
        $this->templateVars[PostsController::DIRECTION] = $options[PostsController::DIRECTION];

        //Sets the order in the template variables
        $this->templateVars[PostsController::ORDER] = $options[PostsController::ORDER];
    }
}