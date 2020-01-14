<?php


namespace Authentication;


use Core\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthenticationController extends Controller
{
    public function signInAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/changePassword.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function changePassword()
    {
        try
        {
            echo $this->twigEnvironment->render('/signIn.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}