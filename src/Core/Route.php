<?php


namespace Core;


class Route
{
    protected string $controller;
    protected string $action;

    public function __construct(array $route)
    {
        $this->setControllerName($route['package'],$route['controller']);

        $this->setAction($route['action']);
    }

    // GETTERS//////////////

    /**
     * Gets the Controller Name
     * Used by Application to instantiate the controller corresponding to the route
     * @return string
     */
    public function getControllerName():string
    {
        return $this->controller;
    }

    /**
     * Gets the action Name
     * Used by Application to call the action corresponding to the route
     * @return string
     */
    public function getActionName():string
    {
        return $this->action;
    }

    //SETTERS///////

    /**
     * Sets the controller name corresponding to the route
     * @param $package
     * @param string $controller
     */
    protected function setControllerName($package, $controller): void
    {
        //Creates a string representing Namespace\Controller
        $this->controller = ucfirst($package).'\\'.ucfirst($controller).'Controller';
    }

    /**
     * Sets the action name corresponding to the route
     * @param string $action
     */
    protected function setAction($action): void
    {
        //Creates a string representing the action method name
        $this->action = $action.'Action';
    }

}