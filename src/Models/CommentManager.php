<?php


namespace Models;


use Core\Manager;
use Entities\Comment;
use PDO;
use PDOStatement;

class CommentManager extends Manager
{
    public const COMMENTS_TABLE = 'comments',
        PSEUDO = 'pseudo',
        CONTENT = 'content',
        POST_ID = 'post_id',
        STATUS = 'status';

    /**
     * Gets a list Comments with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::COMMENTS_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a comment in database
     * @param Comment $comment
     * @return bool
     */
    public function insertComment(Comment $comment):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::COMMENTS_TABLE.'('.self::POST_ID.', '.self::PSEUDO.', '.self::CONTENT.', '.self::STATUS.') 
                        VALUES(:postId, :pseudo, :content , :status)');

        $this->bindAllFields($q,$comment);

        return $q->execute();
    }

    /**
     * Gets a single comment using its id
     * @param int $commentId
     * @return array
     */
    public function findComment(int $commentId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::COMMENTS_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $commentId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given comment
     * @param Comment $comment
     * @return bool
     */
    public function updateComment(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE '.self::COMMENTS_TABLE.' SET '
                                    .self::POST_ID.'= :postId, '.self::PSEUDO.'= :pseudo, '.self::CONTENT.' = :content, '
                                    .self::STATUS.' = :status WHERE id = :id');

        $q->bindValue(':id', $comment->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$comment);

        $q->debugDumpParams();

        return $q->execute();
    }

    /**
     * Removes a specific comment
     * @param int $commentId
     * @return bool
     */
    public function removeComment(int $commentId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::COMMENTS_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$commentId,PDO::PARAM_INT);

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
        $q->bindValue(':status', $comment->getStatus());
    }
}