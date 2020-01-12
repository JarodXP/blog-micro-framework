<?php


namespace Authentication;


class AuthenticationController
{
    public function signinAction()
    {
        require __DIR__.'/Views/signIn.html';
    }

    public function changePassword()
    {
        require __DIR__.'/Views/changePassword.html';
    }
}