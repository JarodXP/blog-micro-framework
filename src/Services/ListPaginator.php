<?php


namespace Services;


use Models\PostManager;

trait
ListPaginator
{
    protected ?array $httpParameters;

    protected ?array $templateVars;

    /**
     * Sets the list options for the lists of elements.
     * Used in admin posts and blogs
     * @return array
     */
    public function paginatedListOptions():array
    {
        //Sets lists options limit
        isset($this->httpParameters[ListConfigurator::LIMIT])
            ? $options[ListConfigurator::LIMIT] = $this->httpParameters[ListConfigurator::LIMIT]
            : $options[ListConfigurator::LIMIT] = 10;

        //Sets lists options offset
        isset($this->httpParameters[ListConfigurator::PAGE])
            ? $options[ListConfigurator::OFFSET] = ($this->httpParameters[ListConfigurator::PAGE]) * $options[ListConfigurator::LIMIT] - $options[ListConfigurator::LIMIT]
            : $options[ListConfigurator::OFFSET] = null;

        //Sets lists options order
        if(isset($this->httpParameters[ListConfigurator::ORDER]))
        {
            $options[ListConfigurator::ORDER] = $this->httpParameters[ListConfigurator::ORDER];
        }
        elseif (isset($this->httpParameters[ListConfigurator::CURRENT_ORDER]))
        {
            $options[ListConfigurator::ORDER] = $this->httpParameters[ListConfigurator::CURRENT_ORDER];
        }
        else
        {
            $options[ListConfigurator::ORDER] = PostManager::DATE_ADDED;
        }

        //Sets lists options direction
        if(isset($this->httpParameters[ListConfigurator::ORDER])
            && isset($this->httpParameters[ListConfigurator::CURRENT_ORDER])
            && isset($this->httpParameters[ListConfigurator::DIRECTION])
            && $this->httpParameters[ListConfigurator::ORDER] == $this->httpParameters[ListConfigurator::CURRENT_ORDER])
        {
            $this->httpParameters[ListConfigurator::DIRECTION] == ListConfigurator::DIRECTION_ASC
                ? $options[ListConfigurator::DIRECTION] = ListConfigurator::DIRECTION_DESC
                : $options[ListConfigurator::DIRECTION] = ListConfigurator::DIRECTION_ASC;
        }
        else
        {
            $options[ListConfigurator::DIRECTION] = ListConfigurator::DIRECTION_ASC;
        }

        return $options;
    }

    /**
     * Sets the variables related to the list to be sent to the twig template
     * @param array $options
     * @param string $listType
     */
    public function paginatedListTwigVariables(array $options, string $listType):void
    {
        //Sets the limit in the template variables
        $this->templateVars[ListConfigurator::LIMIT] = $options[ListConfigurator::LIMIT];

        //Sets the page in the template variables
        isset($this->httpParameters[ListConfigurator::PAGE])
            ? $this->templateVars[ListConfigurator::PAGE] = $this->httpParameters[ListConfigurator::PAGE]
            : $this->templateVars[ListConfigurator::PAGE] = '1';

        //Sets the nextPage in the template variables
        count($this->templateVars[$listType]) >= $options[ListConfigurator::LIMIT]
            ?  $this->templateVars['nextPage'] = $this->templateVars[ListConfigurator::PAGE] + 1
            :  $this->templateVars['nextPage'] = null;

        //Sets the prevPage in the template variables
        (is_null($options[ListConfigurator::OFFSET]) || $options[ListConfigurator::OFFSET] == 0)
            ?  $this->templateVars['prevPage'] = null
            :  $this->templateVars['prevPage'] = $this->templateVars[ListConfigurator::PAGE] - 1;

        //Sets the direction in the template variables
        $this->templateVars[ListConfigurator::DIRECTION] = $options[ListConfigurator::DIRECTION];

        //Sets the order in the template variables
        $this->templateVars[ListConfigurator::ORDER] = $options[ListConfigurator::ORDER];
    }
}