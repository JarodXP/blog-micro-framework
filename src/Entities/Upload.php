<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class Upload extends Entity
{
    protected string $file_name, $originalName;

    protected ?string $alt;


    //GETTERS

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->file_name;
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

        $this->file_name = $fileName;
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

            $this->originalName = $alt;
        }

        $this->alt = $alt;
    }
}