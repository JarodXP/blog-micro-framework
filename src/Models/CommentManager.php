<?php


namespace Models;


use Core\Manager;
use Entities\Comment;
use PDO;
use PDOStatement;
use Services\ListConfigurator;

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
     * Gathers the comments data and the corresponding post data
     * @param $conditions
     * @param $options
     * @return array
     */
    public function findCommentsAndPost($conditions = null, $options = null)
    {
        //Sets the parameters with the ListConfigurator Service
        $listConfigurator = new ListConfigurator($this);

        $requestParameters = $listConfigurator->getRequestParameters($conditions,$options);

        $q = $this->dao->prepare(
            'SELECT comments.*, 
                            posts.title AS postTitle
                        FROM comments 
                        LEFT JOIN posts ON comments.post_id = posts.id'.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
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