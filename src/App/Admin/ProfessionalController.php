<?php


namespace Admin;


use Core\Controller;
use Entities\NetworkLink;
use Entities\SocialNetwork;
use Exceptions\EntityAttributeException;
use Exceptions\UploadException;
use Models\LinkManager;
use Models\NetworkManager;
use Models\UploadManager;
use Models\UserManager;
use PDOException;
use Services\FileUploader;


class ProfessionalController extends Controller
{
    use FileUploader;

    public const NETWORK_NAME = 'name', NEW_NETWORK = 'new-network', REGISTER = 'register';

    //Links and resume

    public function displayProfessionalAction()
    {
        //Get the networks list and corresponding user links
        $networkManager = new NetworkManager();

        $this->templateVars['networks'] = $networkManager->findNetworksAndLinks($_SESSION['user']->getId(),true);

        //Gets the resume and sends it to the twig template
        $userManager = new UserManager();

        $this->templateVars['resume'] = $userManager->findUserResume($_SESSION['user']->getId());

        $this->twigRender('/adminProfessional.html.twig');
    }

    public function registerProfessionalAction()
    {
        //Gets the list of networks
        $networkManager = new NetworkManager();

        $networks = $networkManager->findNetworksAndLinks($_SESSION['user']->getId(),true);

        //Creates or updates a network link for each network
        $linkManager = new LinkManager();

        try
        {
            //Registers the network links
            foreach ($networks as $network)
            {
                if(isset($this->httpParameters[$network['networkName']]))
                {
                    //Creates an instance of Link
                    $link = new NetworkLink([
                        'networkId' => $network['id'],
                        'link' => $this->httpParameters[$network['networkName']],
                        'userId' => $_SESSION['user']->getId()
                    ]);

                    $link->isValid();

                    //inserts or updates link
                    if(is_null($network['linkId']))
                    {
                        $linkManager->insertNetworkLink($link);
                    }
                    else
                    {
                        $link->setId($network['linkId']);

                        $linkManager->updateNetworkLink($link);
                    }
                }
            }

            //Checks if $_FILES['resumeFile'] contains a file
            if($_FILES['resumeFile']['error'] != 4)
            {
                //Uses FileUploader service to upload the resume and get an Upload object
                $resume = $this->uploadPDF('resumeFile');

                //Stores the current resume id
                if(!is_null($_SESSION['user']->getResumeId()))
                {
                    $currentResumeId = $_SESSION['user']->getResumeId();
                }

                //Sets the new resume id property to the $_SESSION['user']
                $_SESSION['user']->setResumeId($resume->getId());

                //Updates user
                $userManager = new UserManager();

                $userManager->updateUser($_SESSION['user']);

                //Removes former resume
                if(isset($currentResumeId))
                {
                    $this->removeFile($currentResumeId);

                }
            }
        }
        catch (PDOException | UploadException | EntityAttributeException $e)
        {

            $this->response->redirect('/admin/professional',$e->getMessage());
        }

        $this->response->redirect('/admin/professional','Les informations ont été mises à jour');
    }

    public function removeLinkAction()
    {
        //Checks if the link id is set
        if(isset($this->httpParameters['link']))
        {
            $linkManager = new LinkManager();

            //Checks if the id matches with an existing link
            $link = $linkManager->findOneBy(['id' => $this->httpParameters['link']]);

            if(!empty($link))
            {
                //Removes the link
                $linkManager->removeElement($link['id']);

                $notification = 'Le lien a bien été supprimé';
            }
            else
            {
                $notification = 'L\'identifiant du lien n\'existe pas';
            }
        }
        else
        {
            $notification = 'Aucun idntifiant de lien n\'a été transmis';
        }

        $this->response->redirect('/admin/professional',$notification);
    }

    public function removeResumeAction()
    {
        //Gets the resume Id from the current user
        $resumeId = $_SESSION['user']->getResumeId();

        //Sets the user's resume id to null on database
        $_SESSION['user']->setResumeId(null);

        $userManager = new UserManager();

        $userManager->updateUser($_SESSION['user']);

        //Removes file in server and database
        $this->removeFile($resumeId);

        $this->response->redirect('/admin/professional', 'Le CV a bien été supprimé');
    }

    //Social Networks

    public function displayNetworksAction()
    {
        //Gets the network list and sends it to the twig template
        $networkManager = new NetworkManager();

        $this->templateVars['networks'] = $networkManager->findNetworksAndIcons();

        //If existing network, sends the network data to the twig template
        if(isset($this->httpParameters['update']))
        {
            $this->templateVars['updateNetwork'] = $networkManager->findNetworksAndIcons([
                'name' => $this->httpParameters['update']
            ])[0];

            //Sends the network name as register key
            $this->templateVars[self::REGISTER] = $this->httpParameters['update'];

            $this->twigRender('/adminUpdateNetwork.html.twig');
        }
        else
        {
            //Sends new-network as register key
            $this->templateVars[self::REGISTER] = self::NEW_NETWORK;

            $this->twigRender('/adminNewNetwork.html.twig');
        }
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
        catch (PDOException | UploadException | EntityAttributeException $e)
        {
            //If a file has been created during process, removes the former one
            if(isset($icon) && isset($network) && !is_null($network->getId()) && isset($currentIconId))
            {
                $this->removeFile($currentIconId);
            }

            //Redirects either to the update network page or the new network page
            $this->response->redirect('/admin/networks/?update='.$this->httpParameters[self::REGISTER],$e->getMessage());
        }
    }

    public function removeNetworkAction()
    {
        //Instantiates the Network manager
        $networkManager = new NetworkManager();

        //Gets the network corresponding to the name
        $network = $networkManager->findOneBy(['name' => $this->httpParameters['remove']]);

        try
        {
            if(!is_null($network['upload_id']))
            {
                //Gets the icon related to the post
                $uploadManager = new UploadManager();

                $icon = $uploadManager->findOneBy(['id' => $network['upload_id']]);
            }

            //Removes the network
            $networkManager->removeElement($network['id']);

            //Removes the image, both in database and server
            if(isset($icon))
            {
                $this->removeFile($icon['id']);
            }
        }
        catch (PDOException | UploadException $e)
        {
            //Redirects to the admin update network page in case of failure
            $this->response->redirect('/admin/networks/?update='.$network['name'], $e->getMessage());
        }

        //Redirects to the admin networks list in case of success
        $this->response->redirect('/admin/networks', 'Le réseau a bien été supprimé');
    }
}