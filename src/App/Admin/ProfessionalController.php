<?php


namespace Admin;


use Core\Controller;
use Entities\SocialNetwork;
use Exceptions\EntityAttributeException;
use Models\NetworkManager;
use Models\UserManager;
use Services\FileUploader;


class ProfessionalController extends Controller
{
    use FileUploader;

    public const NETWORK_NAME = 'name', NEW_NETWORK = 'new-network', REGISTER = 'register';

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

        //Sends the network registerIndex as new-network
        $this->templateVars[self::REGISTER] = self::NEW_NETWORK;

        $this->twigRender('/adminNewNetwork.html.twig');
    }

    public function registerNetworkAction()
    {
        $networkManager = new NetworkManager();

        try
        {
            //If it's a new Network, creates a network instance from the httpParameters
            if($this->httpParameters[self::REGISTER] == self::NEW_NETWORK)
            {
                $network = new SocialNetwork($this->httpParameters);
            }
            //If it's an existing network, creates instance from database
            else
            {
                $network = new SocialNetwork($networkManager->findOneBy([
                    self::NETWORK_NAME => $this->httpParameters[self::REGISTER]]));

                //And updates the properties with the httpParameters
                $network->updateProperties($this->httpParameters);
            }

            //Sets a variable to store the current icon id
            $currentIconId = null;

            //Checks if $_FILES['networkIconFile'] contains a file
            if($_FILES['networkIconFile']['error'] != 4)
            {
                //Uses FileUploader service to upload the post header image and get an Upload object
                $icon = $this->uploadImage('networkIconFile',$this->httpParameters['alt']);

                //Stores the current icon id
                $currentIconId = $network->getUploadId();

                //Sets the new iconId property to the $network object
                $network->setUploadId($icon->getId());
            }

            //Checks if all mandatory properties are set and not null
            $network->isValid();

            //Inserts or updates network depending on the "new-network" parameter
            if($this->httpParameters[self::REGISTER] == self::NEW_NETWORK)
            {
                //Inserts the $post
                $networkManager->insertSocialNetwork($network);
            }
            else
            {
                //Updates the network
                $networkManager->updateSocialNetwork($network);

                //Removes former icon (both in server and database)
                if(!is_null($currentIconId))
                {
                    $this->removeFile($currentIconId);
                }
            }

            //Redirects to the network page
            $this->response->redirect('/admin/networks');

        }
        catch (EntityAttributeException $e)
        {
            //If a file has been created during process, removes the former one
            if(isset($icon) && isset($network) && !is_null($network->getId()) && isset($currentIconId))
            {
                $this->removeFile($currentIconId);
            }

            //Redirects either to the update network page or the new network page
            $this->response->redirect('/admin/networks/'.$this->httpParameters[self::REGISTER],$e->getMessage());
        }
    }

    public function removeNetworksAction()
    {

    }
}