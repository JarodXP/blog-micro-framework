<?php


namespace Services;


use Entities\User;
use Exceptions\PasswordException;
use Models\UserManager;

class PasswordHandler
{
    private UserManager $_manager;

    private string $_username;

    public function __construct(string $username)
    {
        $this->_username = $username;
    }

    /**
     * Checks if the password sent through login matches the registered password
     * @param string $loginPassword
     * @return bool
     * @throws PasswordException
     */
    public function matchPassword(string $loginPassword):bool
    {
        $manager = new UserManager();

        //Finds the user corresponding to the username
        $userData = $manager->findListBy(['username' => $this->_username]);

        if(empty($userData))
        {
            throw new PasswordException('The user doesn\'t exist.');
        }

        //Compares the given password and the one got from database
        if(password_verify($loginPassword,$userData['password']))
        {
            return true;
        }
        else
        {
            throw new PasswordException('Password is not valid');
        }
    }

    /**
     * Changes the $_currentPassword
     * @param string $newPassword
     * @param string $newPasswordCheck
     * @throws PasswordException
     */
    public function changePassword(string $newPassword,string $newPasswordCheck):void
    {
        if($newPassword === $newPasswordCheck)
        {
            $this->_user->setPassword(password_hash($newPassword,PASSWORD_DEFAULT));

            $_SESSION['user']->setNotification('Le mot de passe a été changé avec succès');
        }
        else
        {
            throw new PasswordException('Les nouveaux mots de passe ne sont pas identiques');
        }
    }

}