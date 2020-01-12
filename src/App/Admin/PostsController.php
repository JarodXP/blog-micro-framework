<?php


namespace Admin;


class PostsController
{
    public function postListAction()
    {
        require __DIR__.'/Views/adminPosts.html';
    }

    public function editPostAction()
    {
        require __DIR__.'/Views/adminEditPost.html';
    }

    public function newPostAction()
    {
        require __DIR__.'/Views/adminNewPost.html';
    }

    public function removePostAction(){}
}