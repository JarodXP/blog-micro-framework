<?php


namespace Authentication;


use Core\Controller;
use Core\HttpResponse;
use Entities\User;
use Exceptions\EntityAttributeException;
use Models\UserManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthenticationController extends Controller
{
    public function displayAdminFormAction()
    {
        try
        {
            echo $this->twigEnvironment->render('authRegisterAdmin.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function registerAdminAction()
    {
        //Checks if both passwords fields are exactly the same
        if($this->httpParameters['password'] == $this->httpParameters['passwordCheck'])
        {
            try
            {
                $manager = new UserManager();

                //Instantiates a User
                $admin = new User([
                    'username' => $this->httpParameters['username'],
                    'email' => $this->httpParameters['email'],
                    'password' => $this->httpParameters['password'],
                    'role' => User::ROLE_ADMIN
                ]);

                //Inserts admin in database
                $manager->insertUser($admin);

                //Sets to the user the last id registered
                $admin->setId($manager->lastId());

                //Sets the $_SESSION user
                $_SESSION['user'] = $admin;

                $this->response->redirect('/admin',HttpResponse::ADMIN_REGISTERED);
            }
            catch(EntityAttributeException $e)
            {
                //Redirects to same page in case of typing error
                $response = new HttpResponse();

                $response->redirect('/auth/first-time',$e->getMessage());
            }
        }
        else
        {
            $this->response->redirect('/auth/first-time',HttpResponse::PASSWORD_MISMATCH);
        }
    }

    public function displaySigninFormAction()
    {
        try
        {
            echo $this->twigEnvironment->render('authSignIn.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function signInAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/authChangePassword.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

    public function changePasswordAction()
    {
        try
        {
            echo $this->twigEnvironment->render('/authChangePassword.html.twig');
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }

}