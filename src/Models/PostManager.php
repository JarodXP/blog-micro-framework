<?php


namespace Models;


use Core\Manager;
use Entities\Post;
use PDO;
use PDOStatement;
use Services\ListHandler;

class PostManager extends Manager
{
    public const TABLE = 'posts',
        TITLE = 'title',
        SLUG = 'slug',
        AUTHOR = 'author',
        HEADER_ID = 'header_id',
        EXTRACT = 'extract',
        CONTENT = 'content',
        DATE_ADDED = 'date_added',
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
            self::SLUG => $post->getSlug()
        ],false);

        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::AUTHOR.', '.self::TITLE.', '
                            .self::SLUG.', '.self::HEADER_ID.', '.self::EXTRACT.', '
                            .self::CONTENT.', '.self::STATUS.') 
                        VALUES(:author, :title, :slug, :headerId, :extract, :content, :status)');

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
            self::SLUG => $post->getSlug(),
        ],true,$post->getId());

        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::AUTHOR.'= :author, '.self::TITLE.' = :title,'.self::SLUG.' = :slug,'.self::HEADER_ID.' = :headerId, '
            .self::EXTRACT.' = :extract, '.self::CONTENT.' = :content, '.self::STATUS.' = :status WHERE id = :id');

        $q->bindValue(':id', $post->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$post);

        return $q->execute();
    }

    /**
     * Gathers the post data and the corresponding upload data
     * @param $conditions
     * @param $options
     * @return array
     */
    public function findPostsAndUploads($conditions = null, $options = null)
    {
        //Sets the parameters with the ListHandler Service
        $listManager = new ListHandler($this);

        $requestParameters = $listManager->getRequestParameters($conditions,$options);

        $q = $this->dao->prepare(
            'SELECT posts.*, 
                            uploads.file_name AS fileName,
                            uploads.original_name AS originalName,
                            uploads.alt AS alt
                        FROM posts 
                        LEFT JOIN uploads ON posts.header_id = uploads.id'.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param Post $post
     */
    private function bindAllFields(PDOStatement &$q, Post $post)
    {
        $q->bindValue(':author',$post->getAuthor());
        $q->bindValue(':title',$post->getTitle());
        $q->bindValue(':slug',$post->getSlug());
        $q->bindValue(':headerId',$post->getHeaderId(),PDO::PARAM_INT);
        $q->bindValue(':extract',$post->getExtract());
        $q->bindValue(':content',$post->getContent());
        $q->bindValue(':status',$post->getStatus());
    }
}