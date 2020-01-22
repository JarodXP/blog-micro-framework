<?php


namespace Models;


use Core\Manager;
use Entities\Post;
use PDO;
use PDOStatement;

class PostManager extends Manager
{
    public const TABLE = 'posts',
        TITLE = 'title',
        USER_ID = 'user_id',
        HEADER_ID = 'header_id',
        EXTRACT = 'extract',
        CONTENT = 'content',
        STATUS = 'status';


    /**
     * Inserts a post in database
     * @param Post $post
     * @return bool
     */
    public function insertPost(Post $post):bool
    {
        //Checks if the title is unique
        $this->checkUniqueFields([
            self::TITLE => $post->getTitle(),
        ],false);

        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::USER_ID.', '.self::TITLE.', '
                        .self::HEADER_ID.', '.self::EXTRACT.', '.self::CONTENT.', '.self::STATUS.') 
                        VALUES(:userId, :title, :headerId, :extract, :content, :status)');

        $this->bindAllFields($q,$post);

        return $q->execute();
    }

    /**
     * Updates a given post
     * @param Post $post
     * @return bool
     */
    public function updatePost(Post $post)
    {
        //Checks if the title is unique
        $this->checkUniqueFields([
            self::TITLE => $post->getTitle(),
        ],true,$post->getId());

        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::USER_ID.'= :userId, '.self::TITLE.' = :title,'.self::HEADER_ID.' = :headerId, '
            .self::EXTRACT.' = :extract, '.self::CONTENT.' = :content, '.self::STATUS.' = :status WHERE id = :id');

        $q->bindValue(':id', $post->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$post);

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