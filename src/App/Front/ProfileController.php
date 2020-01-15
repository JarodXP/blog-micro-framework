<?php


namespace Front;


use Core\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfileController extends Controller
{
    public function displayProfileAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/myProfile.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function displayContactFormAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/contact.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function sendContactFormAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/thankYou.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}