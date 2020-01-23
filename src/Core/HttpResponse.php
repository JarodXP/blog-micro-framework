<?php


namespace Core;


class HttpResponse
{
    public const WRONG_LOGIN = 'L\'identifiant saisi n\'existe pas.',
        WRONG_PASSWORD = 'Le mot de passe ne correspond pas.',
        PASSWORD_MISMATCH = 'Les deux champs mot de passe ne sont pas identiques',
        AUTH = 'Veuillez vous identifier pour entrer dans l\'espace admin',
        ADMIN_REGISTERED = 'Admin user has been registered';


    /**
     * Sets the SESSION['user'] notification and redirects with header.
     * @param string $location
     * @param int|null $code
     * @param string|null $notification
     */
    public function redirect(string $location, string $notification = null):void
    {
        $_SESSION['user']->setNotification($notification);

        $location = 'location:'.$location;

        header($location);

        exit();
    }
}