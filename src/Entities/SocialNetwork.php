<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class SocialNetwork extends Entity
{
    protected ?int $uploadId;

    protected ?string $name;

    //GETTERS

    /**
     * @return int
     */
    public function getUploadId():?int
    {
        return $this->uploadId;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    //SETTERS

    /**
     * @param int $uploadId
     */
    public function setUploadId(int $uploadId = null): void
    {
        $this->uploadId = $uploadId;
    }

    /**
     * @param string $name
     */
    public function setName(string $name = null): void
    {
        if(mb_strlen($name) > 30)
        {
            throw new EntityAttributeException('$name should be less than 30 characters.');
        }
        $this->name = $name;
    }

    /**
     * Sets an array with the mandatory fields
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['uploadId','name'];
    }
}