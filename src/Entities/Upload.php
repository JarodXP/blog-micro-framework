<?php


namespace Entities;


use Core\Entity;

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
     * @param string $file_name
     */
    public function setFileName(string $file_name): void
    {
        $this->file_name = $file_name;
    }

    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName): void
    {
        $this->originalName = $originalName;
    }

    /**
     * @param string $alt
     */
    public function setAlt(string $alt = null): void
    {
        $this->alt = $alt;
    }
}