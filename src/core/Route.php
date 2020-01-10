<?php


namespace Core;


class Route
{
    protected $uri,
        $module,
        $action;

    public function __construct(array $route)
    {
        $this->setUri($route['uri']);

        $this->setModule($route['module']);

        $this->setAction($route['action']);
    }

    // GETTERS//////////////

    /**
     * @return string
     */
    public function getUri():string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getModule():string
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function getAction():string
    {
        return $this->action;
    }

    //SETTERS///////

    /**
     * @param string $uri
     */
    protected function setUri($uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @param string $module
     */
    protected function setModule($module): void
    {
        $this->module = $module;
    }

    /**
     * @param string $action
     */
    protected function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * Checks if the route URI pattern matches the URI requested
     * @param $uri
     * @return bool
     */
    public function match($uri)
    {
        if(preg_match('~^'.$this->uri.'$~', $uri, $matches))
        {
            return $matches;
        }

        return false;
    }
}