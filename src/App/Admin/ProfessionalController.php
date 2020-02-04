<?php


namespace Admin;


use Core\Controller;
use Models\UserManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfessionalController extends Controller
{
    public function displayProfessionalAction()
    {
        //Gets the resume and sends it to the twig template
        $userManager = new UserManager();

        $this->templateVars['profile'] = $userManager->findProfile(['users.id' => $_SESSION['user']->getId()]);

        $this->twigRender('/adminProfessional.html.twig');
    }

    public function registerProfessionalAction()
    {

    }

    public function displayNetworksAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/adminSocial.html.twig');
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