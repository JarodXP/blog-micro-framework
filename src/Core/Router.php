<?php


namespace Core;


use Entities\User;
use Exceptions\RoutingException;
use HTMLPurifier;
use HTMLPurifier_Config;
use Models\UserManager;
use Services\AuthenticationHandler;

class Router
{
    use AuthenticationHandler;

    protected Route $route;

    protected HttpResponse $response;

    protected array $httpParameters = [];

    public function __construct(string $uri)
    {
        //Sets the HttpResponse property
        $this->response = new HttpResponse();

        //Sets the route array property
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

    /**
     * Gets the sanitized HTTP parameters
     * @return array|null
     */
    public function getHttpParameters():?array
    {
        return $this->httpParameters;
    }

    //SUB METHODS//////////////////////////

    /**
     * Looks into routes.yml file and sets the Route.
     * @param string $uri
     */
    protected function setRoute(string $uri):void
    {
        //If it's first connection, redirects to register admin form
        $uri = $this->firstConnectionFilter($uri);

        //Prevents unauthorized user to access admin
        if(!strpos($uri,'admin') === false && !$this->authenticatedAsAdmin())
        {
            //Redirects
            $this->response->redirect('/auth',HttpResponse::AUTH);
        }

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

        //Sets the httpParameters
        $this->sanitizeHttpParams();
    }

    /**
     * Sets an array with both GET and POST params sanitized with HTML Purifier
     * @return void
     */
    private function sanitizeHttpParams():void
    {
        //Sets the config for HTML Purifier
        $config = HTMLPurifier_Config::createDefault();

        $purifier = new HTMLPurifier($config);

        //Sanitizes every GET parameter
        foreach ($_GET as $item => $value)
        {
            $this->httpParameters[$item] = $purifier->purify($value);
        }

        //Sanitizes every POST parameter
        foreach ($_POST as $item => $value)
        {
            $this->httpParameters[$item] = $purifier->purify($value);
        }
    }

    /**
     * Checks if an admin exists and redirects to admin form
     * @param $uri
     * @return string
     */
    private function firstConnectionFilter(string $uri):string
    {
        $manager = new UserManager();

        //Looks for user with admin rights
        if(empty($manager->findListBy(['role' => User::ROLE_ADMIN])) && !preg_match('~^/auth/register$~',$uri))
        {
            $uri = '/auth/first-time';
        }

        return $uri;
    }
}