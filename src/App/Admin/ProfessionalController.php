<?php


namespace Admin;


use Core\Controller;
use Models\NetworkManager;
use Models\UserManager;


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
        //Gets the network list and sends it to the twig template
        $networkManager = new NetworkManager();

        $this->templateVars['networks'] = $networkManager->findNetworksAndIcons();

        $this->twigRender('/adminSocial.html.twig');
    }

    public function addNetworksAction()
    {

    }

    public function removeNetworksAction()
    {

    }
}