<?php


namespace Admin;


class IndexController
{
    public function homeAction()
    {
        header('location:/admin/posts');
        exit();
    }
}