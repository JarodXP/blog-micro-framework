<?php


namespace Front;


class ProfileController
{
    public function displayProfileAction()
    {
        require __DIR__.'/Views/myProfile.html';
    }

    public function displayContactFormAction()
    {
        require __DIR__.'/Views/contact.html';
    }

    public function sendContactFormActionAction()
    {
        require __DIR__.'/Views/thankYou.html';
    }
}