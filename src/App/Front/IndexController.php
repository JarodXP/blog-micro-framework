<?php


namespace Front;


class IndexController
{
    public function homeAction()
    {
        header('location:/profile/');
        exit();
    }
}