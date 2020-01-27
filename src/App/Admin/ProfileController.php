<?php


namespace Admin;


use Core\Controller;
use Core\HttpResponse;
use Entities\Upload;
use Entities\User;
use Exceptions\EntityAttributeException;
use Models\UploadManager;
use Models\UserManager;
use Services\AuthenticationHandler;
use Services\EntityUpdater;


class ProfileController extends Controller
{
    use AuthenticationHandler, EntityUpdater;

    public function editProfileAction()
    {
        if($this->authenticatedAsAdmin())
        {
            //Sets the template var 'user'
            $this->templateVars['user'] = $_SESSION['user'];

            //Creates an instance for avatar with avatar id from the user's property
            $uploadManager = new UploadManager();

            //Sets the template var 'avatar'
            if(!is_null($_SESSION['user']->getAvatarId()))
            {
                $this->templateVars['avatar'] = new Upload($uploadManager->findOneBy(['id' => $_SESSION['user']->getAvatarId()]));
            }

            $this->twigRender('/adminMyProfile.html.twig',$this->templateVars);
        }

        $this->response->redirect('/auth',HttpResponse::AUTH);
    }

    public function registerProfileAction()
    {
        //Creates a new User instance from the $_SESSION['user'] cookie
        $userManager = new UserManager();

        $adminData = $userManager->findListBy(['id' => $_SESSION['user']->getId()])[0];

        $admin = new User($adminData);

        //Uses EntityUpdater service to update the $admin object with the parameters
        $this->updateProperties($this->httpParameters,$admin);

        $manager = new UserManager();

        try
        {
            //Checks if all mandatory properties are set and not null
            $admin->isValid();

            //Updates the user
            $manager->updateUser($admin);

        }
        catch (EntityAttributeException $e)
        {
            //In case not all the mandatory properties are valid, redirects to profile
            $this->response->redirect('/admin/my-profile',$e->getMessage());
        }

        //Updates the $_SESSION cookie
        $_SESSION['user'] = $admin;

        $this->editProfileAction();
    }
}