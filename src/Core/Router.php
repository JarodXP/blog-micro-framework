<?php


namespace Core;


use Exceptions\RoutingException;

class Router
{
    protected Route $route;

    public function __construct(string $uri)
    {
        try
        {
            $this->setRoute($uri);
        }
        catch (RoutingException $e)
        {
            print_r($e->getMessage());
            exit();
        }
    }

    //PUBLIC METHODS/////////////////////////

    /**
     * Gets the matching Route stored.
     * To be used by Application to get route information
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

        //Throws error in case of route not found
        if(empty($this->route))
        {
            throw new RoutingException('The requested page doesn\'t exist');
        }
    }
}