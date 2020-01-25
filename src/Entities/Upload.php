<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class Upload extends Entity
{
    protected string $fileName, $originalName;

    protected ?string $alt;

    protected ?int $type;

    public const IMAGE_TYPE = 1, PDF_TYPE = 2;


    //GETTERS

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    //SETTERS

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        if(!preg_match('~^[a-zA-Z0-9-.\\\/_]{2,100}.(pdf|jpg|jpeg|png)$~',$fileName))
        {
            throw new EntityAttributeException('File name is not valid');
        }

        $this->fileName = $fileName;
    }

    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName): void
    {
        if(!preg_match('~^[a-zA-Z0-9-._]{2,100}.(pdf|jpg|jpeg|png)$~',$originalName))
        {
            throw new EntityAttributeException('File name is not valid');
        }

        $this->originalName = $originalName;
    }

    /**
     * @param string $alt
     */
    public function setAlt(string $alt = null): void
    {
        if(!is_null($alt))
        {
            if(!preg_match('~^[a-zA-Z0-9-._]{2,30}$~',$alt))
            {
                throw new EntityAttributeException('alt name is not valid');
            }

            $this->alt = $alt;
        }

        $this->alt = $alt;
    }

    /**
     * @param int|null $type
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
    }

}