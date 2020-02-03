<?php


namespace Entities;


use Cocur\Slugify\Slugify;
use Core\Entity;
use Exceptions\EntityAttributeException;
use Models\UserManager;

class Post extends Entity
{
    protected string $author;
    protected bool $status;
    protected ?int $headerId;
    protected ?string $title;
    protected ?string $extract;
    protected ?string $content;
    protected ?string $dateAdded;
    protected ?string $dateModified;
    protected string $slug;

    public const STATUS_PUBLISHED = 1, STATUS_DRAFT = 0;


    //GETTERS

    /**
     * @return int
     */
    public function getAuthor(): string
    {
        return $this->author;
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
     * @param int $author
     */
    public function setAuthor(string $author): void
    {
        $userManager = new UserManager();

        //Checks if author is an existing username
        if(empty($userManager->findOneBy(['username' => $author])))
        {
            throw new EntityAttributeException('Author should be a valid user');
        }
        else
        {
            $this->author = $author;
        }
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
    public function setStatus(bool $status = null): void
    {
        //Checks if status is null and sets a default value to false
        if(is_null($status))
        {
            $status = false;
        }

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
    protected function setDateAdded(string $dateAdded = null): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @param string $dateModified
     */
    protected function setDateModified(string $dateModified = null): void
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug = null): void
    {
        if(!is_null($slug))
        {
            //Checks if existing slug matches regex pattern
            if(!preg_match('~^[a-z0-9\-]{5,30}$~',$slug))
            {
                throw new EntityAttributeException('Le slug doit avoir entre 5 et 30 caractères et ne 
            comprendre que des lettres minuscules des chiffres et le tiret -');
            }
        }
        else
        {
            //Creates a slug from the title
            $slugify = new Slugify();

            $slug = $slugify->slugify($this->title);
        }

        $this->slug = $slug;
    }

    /**
     * Sets an array with the mandatory fields
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['title','author','slug','status'];
    }
}