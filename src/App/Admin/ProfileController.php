<?php


namespace Admin;


use Core\Controller;
use Core\HttpResponse;
use Entities\Upload;
use Models\UploadManager;
use Services\AuthenticationHandler;


class ProfileController extends Controller
{
    use AuthenticationHandler;

    public function editProfileAction()
    {
        if($this->authenticatedAsAdmin())
        {
            //Sets the template var 'user'
            $this->templateVars['user'] = $_SESSION['user'];

            //Creates an instance for avatar with avatar id from the user's property
            $uploadManager = new UploadManager();

            //Sets the template var 'avatar'
            $this->templateVars['avatar'] = new Upload($uploadManager->findOneBy(['id' => $_SESSION['user']->getAvatarId()]));

            $this->twigRender('/adminMyProfile.html.twig',$this->templateVars);

        }

        $this->response->redirect('/auth',HttpResponse::AUTH);
    }

    public function registerProfileAction()
    {

    }
}