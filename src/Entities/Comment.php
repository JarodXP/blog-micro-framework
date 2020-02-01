<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class Comment extends Entity
{
    protected int $postId;
    protected bool $status;
    protected string $pseudo, $content;
    protected ?string $dateAdded;

    public const STATUS_PUBLISHED = 1,
        STATUS_MODERATE = 0;

    //GETTERS

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return int
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDateAdded(): string
    {
        return $this->dateAdded;
    }

    //SETTERS

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @param int $status
     */
    public function setStatus(bool $status = null): void
    {
        if(is_null($status))
        {
            $this->status = false;
        }
        else
        {
            $this->status = $status;
        }
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        if(!preg_match('~^[a-zA-Z0-9]{2,20}$~',$pseudo))
        {
            throw new EntityAttributeException('Pseudo is not valid');
        }

        $this->pseudo = $pseudo;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        if(mb_strlen($content) > 300)
        {
            throw new EntityAttributeException('Content should be less than 300 characters');
        }

        $this->content = $content;
    }

    /**
     * @param string $dateAdded
     */
    protected function setDateAdded(string $dateAdded = null): void
    {
        $this->dateAdded = $dateAdded;
    }


    /**
     * Sets an array with the mandatory fields
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['postId','pseudo','content'];
    }
}