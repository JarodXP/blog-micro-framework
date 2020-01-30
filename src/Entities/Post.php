<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class Post extends Entity
{
    protected int $userId;
    protected bool $status;
    protected ?int $headerId;
    protected ?string $title;
    protected ?string $extract;
    protected ?string $content;
    protected string $slug, $dateAdded, $dateModified;


    //GETTERS

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getHeaderId(): ?int
    {
        return $this->headerId;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getExtract(): ?string
    {
        return $this->extract;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
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

    /**
     * @return string
     */
    public function getDateModified(): string
    {
        return $this->dateModified;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    //SETTERS

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param int $headerId
     */
    public function setHeaderId(int $headerId = null): void
    {
        $this->headerId = $headerId;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title = null): void
    {
        if(mb_strlen($title) > 70)
        {
            throw new EntityAttributeException('Title should be less than 70 characters');
        }

        $this->title = $title;
    }

    /**
     * @param string $extract
     */
    public function setExtract(string $extract = null): void
    {
        if(mb_strlen($extract) > 160)
        {
            throw new EntityAttributeException('Extract should be less than 160 characters');
        }

        $this->extract = $extract;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content = null): void
    {
        if(mb_strlen($content) > 5000)
        {
            throw new EntityAttributeException('Content should be less than 5000 characters');
        }

        $this->content = $content;
    }

    /**
     * @param string $dateAdded
     */
    protected function setDateAdded(string $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @param string $dateModified
     */
    protected function setDateModified(string $dateModified): void
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Sets an array with the mandatory fields
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['title','user_id','slug','status'];
    }
}