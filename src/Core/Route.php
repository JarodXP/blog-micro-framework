<?php


namespace Core;


class Route
{
    protected string $requestedUri;
    protected string $controller;
    protected string $action;

    public function __construct(array $route)
    {
        $this->setRequestedUri($route['requestedUri']);

        $this->setController($route['package'],$route['controller']);

        $this->setAction($route['action']);
    }

    // GETTERS//////////////

    /**
     * @return string
     */
    public function getRequestedUri():string
    {
        return $this->requestedUri;
    }

    /**
     * @return string
     */
    public function getController():string
    {
        return $this->controller;
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
    protected function setRequestedUri($uri): void
    {
        $this->requestedUri = $uri;
    }

    /**
     * @param $package
     * @param string $controller
     */
    protected function setController($package,$controller): void
    {
        $this->controller = ucfirst($package).'\\'.ucfirst($controller).'Controller';
    }

    /**
     * @param string $action
     */
    protected function setAction($action): void
    {
        $this->action = $action.'Action';
    }

}