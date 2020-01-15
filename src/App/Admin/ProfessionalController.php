<?php


namespace Admin;


use Core\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfessionalController extends Controller
{
    public function displayProfessionalAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/professional.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function registerProfessionalAction()
    {

    }

    public function displayNetworksAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/social.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function addNetworksAction()
    {

    }

    public function removeNetworksAction()
    {

    }
}