<?php


namespace Entities;


use Core\Entity;

class Post extends Entity
{
    protected int $userId, $headerId, $status;

    protected string $title, $extract, $content, $dateAdded, $dateModified;

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
    public function getHeaderId(): int
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getExtract(): string
    {
        return $this->extract;
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

    /**
     * @return string
     */
    public function getDateModified(): string
    {
        return $this->dateModified;
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
    public function setHeaderId(int $headerId): void
    {
        $this->headerId = $headerId;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $extract
     */
    public function setExtract(string $extract): void
    {
        $this->extract = $extract;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param string $dateAdded
     */
    public function setDateAdded(string $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @param string $dateModified
     */
    public function setDateModified(string $dateModified): void
    {
        $this->dateModified = $dateModified;
    }
}