<?php


namespace Front;


class BlogController
{
    public function postListAction()
    {
        require __DIR__.'/Views/blog.html';
    }

    public function displayPostAction()
    {
        require __DIR__.'/Views/post.html';
    }
}