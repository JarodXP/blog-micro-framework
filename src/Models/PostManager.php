<?php


namespace Models;


use Core\Manager;
use Entities\Post;
use PDO;
use PDOStatement;

class PostManager extends Manager
{
    public const POSTS_TABLE = 'posts',
        TITLE = 'title',
        USER_ID = 'user_id',
        HEADER_ID = 'header_id',
        EXTRACT = 'extract',
        CONTENT = 'content',
        STATUS = 'status';

    /**
     * Gets a list of Posts with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::POSTS_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a post in database
     * @param Post $post
     * @return bool
     */
    public function insertPost(Post $post):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::POSTS_TABLE.'('.self::USER_ID.', '.self::TITLE.', '
                        .self::HEADER_ID.', '.self::EXTRACT.', '.self::CONTENT.', '.self::STATUS.') 
                        VALUES(:userId, :title, :headerId, :extract, :content, :status)');

        $this->bindAllFields($q,$post);

        return $q->execute();
    }

    /**
     * Gets a single post using its id
     * @param int $postId
     * @return array
     */
    public function findPost(int $postId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::POSTS_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $postId, PDO::PARAM_INT);

        $q->execute();
        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given post
     * @param Post $post
     * @return bool
     */
    public function updatePost(Post $post)
    {
        $q = $this->dao->prepare('UPDATE '.self::POSTS_TABLE.' SET '
            .self::USER_ID.'= :userId, '.self::TITLE.' = :title,'.self::HEADER_ID.' = :headerId, '
            .self::EXTRACT.' = :extract, '.self::CONTENT.' = :content, '.self::STATUS.' = :status WHERE id = :id');

        $q->bindValue(':id', $post->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$post);

        return $q->execute();
    }

    /**
     * Removes a specific post
     * @param int $postId
     * @return bool
     */
    public function removePost(int $postId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::POSTS_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$postId,PDO::PARAM_INT);

        return $q->execute();
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param Post $post
     */
    private function bindAllFields(PDOStatement &$q, Post $post)
    {
        $q->bindValue(':userId',$post->getUserId(),PDO::PARAM_INT);
        $q->bindValue(':title',$post->getTitle());
        $q->bindValue(':headerId',$post->getHeaderId(),PDO::PARAM_INT);
        $q->bindValue(':extract',$post->getExtract());
        $q->bindValue(':content',$post->getContent());
        $q->bindValue(':status',$post->getStatus());
    }
}