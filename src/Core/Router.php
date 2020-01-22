<?php


namespace Core;


use Entities\User;
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
        //Prevents unauthorized user to access admin
        $this->adminKeeper($uri);

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

    /**
     * Prevents an unauthorized user to access admin
     * @param $uri
     */
    private function adminKeeper($uri)
    {
        //Checks if the admin word is contained in the uri
        if(!strpos($uri,'admin') === false)
        {
            if(!isset($_SESSION['user']->role) || $_SESSION['user']->getRole() != User::ROLE_ADMIN )
            {
                //Redirects
                $response = new HttpResponse();

                $response->redirect('/auth',HttpResponse::AUTH);
            }
        }
    }
}