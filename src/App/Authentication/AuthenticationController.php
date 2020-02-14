<?php


namespace Authentication;


use App\Application;
use Core\Controller;
use Core\HttpResponse;
use Entities\User;
use Exceptions\EntityAttributeException;
use Models\UserManager;
use Services\AuthenticationHandler;

class AuthenticationController extends Controller
{
    protected UserManager $manager;

    use AuthenticationHandler;

    public function __construct(Application $app, array $httpParameters)
    {
        parent::__construct($app, $httpParameters);

        $this->manager = new UserManager();
    }

    public function displayAdminFormAction()
    {
        $this->twigRender('authRegisterAdmin.html.twig');
    }

    public function registerAdminAction()
    {
        //Checks if both passwords fields are exactly the same
        if($this->passwordFieldCheck($this->httpParameters['password'],$this->httpParameters['passwordCheck'],
            '/auth/first-time'))
        {
            try
            {
                //Instantiates a User
                $admin = new User([
                    'username' => $this->httpParameters['username'],
                    'email' => $this->httpParameters['email'],
                    'password' => $this->httpParameters['password'],
                    'role' => User::ROLE_ADMIN
                ]);

                //Inserts admin in database
                $this->manager->insertUser($admin);

                //Sets to the user the last id registered
                $admin->setId($this->manager->lastId());

                //Sets the $_SESSION user
                $_SESSION['user'] = $admin;

                $this->response->redirect('/admin',HttpResponse::ADMIN_REGISTERED);
            }
            catch(EntityAttributeException $e)
            {
                //Redirects to same page in case of typing error
                $this->response->redirect('/auth/first-time',$e->getMessage());
            }
        }
    }

    public function displaySigninFormAction()
    {
        $this->twigRender('authSignIn.html.twig');
    }

    public function signInAction()
    {
        //Searches the user by the login
        $userData = $this->manager->findOneBy(['username' => $this->httpParameters['login']]);

        //If no user were found, redirects
        if(empty($userData))
        {
            $this->response->redirect('/auth',HttpResponse::WRONG_LOGIN);
        }

        //Instantiates the user
        $user = new User($userData);

        //Checks if typed password matches user's password
        if($this->passwordMatch($this->httpParameters['loginPassword'],$user,'/auth'))
        {
            //Sets the user instance as a the new $_SESSION['user']
            $_SESSION['user'] = $user;

            $this->response->redirect('/admin');
        }
    }

    public function displayChangePasswordFormAction()
    {
        $this->twigRender('/authChangePassword.html.twig');
    }

    public function changePasswordAction()
    {
        //Checks the authentication
        $this->isAuthenticated();

        //Checks if the fields were filled and sets a default empty value
        isset($this->httpParameters['currentPassword'])
            ? $currentPassword = $this->httpParameters['currentPassword']
            : $currentPassword = '';

        isset($this->httpParameters['newPassword'])
            ? $newPassword = $this->httpParameters['newPassword']
            : $newPassword = '';

        isset($this->httpParameters['newPasswordCheck'])
            ? $newPasswordCheck = $this->httpParameters['newPasswordCheck']
            : $newPasswordCheck = '';


        //Checks if typed password matches user's password and Checks if newPassword matches newPasswordCheck
        if($this->passwordMatch($currentPassword,$_SESSION['user'],'/admin/change-password-form')
        && $this->passwordFieldCheck($newPassword,$newPasswordCheck,'/admin/change-password-form'))
        {
            //Sets the new password to the session user
            $_SESSION['user']->setPassword($this->httpParameters['newPassword']);
        }

        //updates the user in database
        $this->manager->updateUser($_SESSION['user']);

        //Redirects to the admin profile page
        $this->response->redirect('/admin/my-profile',HttpResponse::PASSWORD_UPDATED);
    }

    public function disconnectAction()
    {
        session_destroy();

        $this->response->redirect('/auth/signin');
    }
}