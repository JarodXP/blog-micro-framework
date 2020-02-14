<?php


namespace Services;


use Core\HttpResponse;
use Entities\User;
use Exceptions\AuthenticationException;

trait AuthenticationHandler
{
    protected HttpResponse $response;

    /**
     * Checks if the $_SESSION is related to an authenticated user
     */
    public function isAuthenticated():void
    {
        if(is_null($_SESSION['user']->getId()))
        {
            throw new AuthenticationException('User is not registered');
        }
    }

    /**
     * Checks if current user has admin role
     * @return bool
     */
    public function authenticatedAsAdmin():bool
    {
        //Checks if $_SESSION['user'] is set with role admin
        return isset($_SESSION['user']) && $_SESSION['user']->getRole() == User::ROLE_ADMIN;
    }

    /**
     * Checks if typed password matches registered password hash
     * @param string $typedPassword
     * @param User $user
     * @param string $redirectUriError
     * @return bool
     */
    public function passwordMatch(string $typedPassword, User $user, string $redirectUriError = '/auth'):bool
    {
       //Compares typed password and hash
        if(!password_verify($typedPassword,$user->getPassword()))
        {
            //Redirects in case of error
            $this->response->redirect($redirectUriError,HttpResponse::WRONG_PASSWORD);

            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks that both 'password' and 'passwordCheck' fields are identical
     * @param string $password
     * @param string $passwordCheck
     * @param string $redirectUriError
     * @return bool
     */
    public function passwordFieldCheck(string $password, string $passwordCheck, string $redirectUriError):bool
    {
        //Compares fields
        if($password != $passwordCheck)
        {
            //Redirects in case of error
            $this->response->redirect($redirectUriError,HttpResponse::PASSWORD_MISMATCH);

            return false;
        }
        else
        {
            return true;
        }
    }
}