<?php


namespace Admin;


class CommentsController
{
    public function commentsListAction()
    {
        require __DIR__.'/Views/adminComments.html';
    }

    public function editCommentAction()
    {
        require __DIR__.'/Views/adminEditComment.html';

    }

    public function removeCommentAction()
    {

    }
}