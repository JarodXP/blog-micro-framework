<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class Upload extends Entity
{
    protected string $fileName, $originalName;

    protected ?string $alt, $type;

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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    //SETTERS

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName): void
    {
        if(preg_match('~^[a-zA-Z0-9-._]{2,100}.(pdf|jpg|jpeg|png)$~',$originalName) == 0)
        {
            throw new EntityAttributeException('File name : '.$originalName.' is not valid');
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
            if(!preg_match('~^[a-zA-Z0-9-._\s]{2,30}$~',$alt))
            {
                throw new EntityAttributeException('alt name is not valid');
            }

            $this->alt = $alt;
        }

        $this->alt = $alt;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * Sets an array with the mandatory fields
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['fileName','originalName','type'];
    }
}