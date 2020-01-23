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
        $manager = new UserManager();

        //Searches the user by the login
        $userData = $manager->findListBy(['username' => $this->httpParameters['login']])[0];

        //If no user were found, redirects
        if(empty($userData))
        {
            $this->response->redirect('/auth',HttpResponse::WRONG_LOGIN);
        }

        //Instantiates the user to get the password
        $user = new User($userData);

        $userPassword = $user->getPassword();

        //Checks if typed password matches user's password
        if(!password_verify($this->httpParameters['loginPassword'],$userPassword))
        {
            //Redirects
            $this->response->redirect('/auth',HttpResponse::WRONG_PASSWORD);
        }
        else
        {
            //Transfers the current notification to the User instance
            $user->setNotification($_SESSION['user']->getNotification());

            //Sets the user instance as a the new $_SESSION['user']
            $_SESSION['user'] = $user;

            $this->response->redirect('/admin');
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