<?php


namespace Models;


use Core\Manager;
use Entities\Comment;
use PDO;
use PDOStatement;

class CommentManager extends Manager
{
    public const TABLE = 'comments',
        PSEUDO = 'pseudo',
        CONTENT = 'content',
        POST_ID = 'post_id',
        STATUS = 'status';


    /**
     * Inserts a comment in database
     * @param Comment $comment
     * @return bool
     */
    public function insertComment(Comment $comment):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::POST_ID.', '.self::PSEUDO.', '.self::CONTENT.', '.self::STATUS.') 
                        VALUES(:postId, :pseudo, :content , :status)');

        $this->bindAllFields($q,$comment);

        $q->debugDumpParams();

        return $q->execute();
    }

    /**
     * Updates a given comment
     * @param Comment $comment
     * @return bool
     */
    public function updateComment(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
                                    .self::POST_ID.'= :postId, '.self::PSEUDO.'= :pseudo, '.self::CONTENT.' = :content, '
                                    .self::STATUS.' = :status WHERE id = :id');

        $q->bindValue(':id', $comment->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$comment);

        return $q->execute();
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param Comment $comment
     */
    private function bindAllFields(PDOStatement &$q, Comment $comment)
    {
        $q->bindValue(':postId',$comment->getPostId(),PDO::PARAM_INT);
        $q->bindValue(':pseudo', $comment->getPseudo());
        $q->bindValue(':content', $comment->getContent());
        $q->bindValue(':status', $comment->getStatus(),PDO::PARAM_BOOL);
    }
}