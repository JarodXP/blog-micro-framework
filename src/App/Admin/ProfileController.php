<?php


namespace Admin;


use Core\Controller;
use Core\HttpResponse;
use Entities\Upload;
use Entities\User;
use Exceptions\EntityAttributeException;
use Exceptions\UploadException;
use Models\UploadManager;
use Models\UserManager;
use Services\AuthenticationHandler;
use Services\FileUploader;


class ProfileController extends Controller
{
    use AuthenticationHandler, FileUploader;

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

            $this->twigRender('/adminMyProfile.html.twig');
        }

        $this->response->redirect('/auth',HttpResponse::AUTH);
    }

    public function registerProfileAction()
    {
        //Creates a new $admin User instance from the $_SESSION['user'] cookie
        $userManager = new UserManager();

        $admin = new User($userManager->findOneBy(['id' => $_SESSION['user']->getId()]));

        //Updates $admin User with the new parameters
        $admin->updateProperties($this->httpParameters);

        //Sets a variable to store the current $admin avatarId
        $currentAvatarId = null;

        try
        {
            //Checks if $_FILES['avatarImageFile'] contains a file
            if(($_FILES['avatarImageFile']['error'] != 4))
            {
                //Uses FileUploader service to upload the avatar image and get an Upload object
                $avatar = $this->uploadImage('avatarImageFile','avatar');

                //Stores the current $admin avatarId
                $currentAvatarId = $admin->getAvatarId();

                //Sets the new avatarId property to the $admin object
                $admin->setAvatarId($avatar->getId());
            }

            //Checks if all mandatory properties are set and not null
            if($admin->isValid())
            {
                //Updates the user
                $userManager->updateUser($admin);

                //Removes former avatar (both in server and database)
                if(!is_null($currentAvatarId))
                {
                    $this->removeFile($currentAvatarId);
                }
            }
        }
        catch (EntityAttributeException | UploadException $e)
        {
            //If a file has been created during process, removes it
            if(isset($avatar) && !is_null($avatar->getId()))
            {
                $this->removeFile($currentAvatarId);
            }

            //In case not all the mandatory properties are valid, redirects to profile
            $this->response->redirect('/admin/my-profile',$e->getMessage());
        }

        //Updates the $_SESSION cookie
        $_SESSION['user'] = $admin;

        $this->editProfileAction();
    }
}