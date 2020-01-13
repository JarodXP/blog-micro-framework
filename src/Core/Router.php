<?php


namespace Core;


class Router
{
    protected Route $route;

    public function __construct(string $uri)
    {
        $this->setRoute($uri);
    }

    //PUBLIC METHODS/////////////////////////

    /**
     * @return Route|null
     */
    public function getRoute():?Route
    {
        return $this->route;
    }

    //SUB METHODS//////////////////////////

    /**
     * Looks into routes.yml file and sets the Route.
     * @param string $uri
     */
    protected function setRoute(string $uri):void
    {
        //Checks all the routes
        foreach ($GLOBALS['routes'] as $route)
        {
            //If matches pattern, sets the $route attribute
            if(preg_match('~^'.$route['uri'].'$~', $uri))
            {
                //Sets the route
                $this->route = new Route($route);
            }
        }
    }
}