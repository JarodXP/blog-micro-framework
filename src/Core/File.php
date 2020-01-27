<?php


namespace Core;



use Entities\Upload;
use Errors\UnauthorizedFileUpload;
use Errors\UploadError;
use Exceptions\UploadException;
use finfo;

class File
{
    private string $_fileName, $_originalName, $_tempFile, $_mimeType;

    private int $_size;

    public function __construct(array $file)
    {
        //Checks if no upload errors occurred
        if($this->checkErrorCode($file['error']))
        {
            //Checks if file _size doesn't exceeds allowed
            if($this->checkFileSize($file['size']))
            {
                //Sets the files properties
                $this->_originalName = $file['name'];

                $this->_tempFile = $file['tmp_name'];

                $this->_size = $file['size'];

                $this->_mimeType = $file['type'];

                $this->setFileName();
            }
        }
    }

    //GETTERS

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->_fileName;
    }

    /**
     * @return mixed|string
     */
    public function getTempFile()
    {
        return $this->_tempFile;
    }

    /**
     * @return mixed|string
     */
    public function getMimeType()
    {
        return $this->_mimeType;
    }

    /**
     * @return int|mixed
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @return mixed|string
     */
    public function getOriginalName()
    {
        return $this->_originalName;
    }


    //PRIVATE FUNCTIONS


    /**
     * Checks if an error occurred during upload and throws an exception in case of problem
     * @param int $errorCode
     * @return bool
     */
    private function checkErrorCode(int $errorCode):bool
    {
        if($errorCode != 0)
        {
            throw new UploadException('Une erreur est survenue lors du téléchargement du fichier');
        }

        else
        {
            return true;
        }
    }

    /**
     * Checks the file _size and throws an exception in case of problem
     * @param int $size
     * @return bool
     */
    private function checkFileSize(int $size):bool
    {
        //Gets the max _size allowed for files in config
        $maxSize = $GLOBALS['config']['MAX_SIZE'];

        if($size > $maxSize)
        {
            throw new UploadException('La taille du fichier ne doit pas être supérieure à '.($maxSize/1000).' Ko');
        }
        else
        {
            return true;
        }
    }

    /**
     * Sets the file name by defining a random name
     */
    private function setFileName():void
    {
        $this->_fileName = str_shuffle((string) time().mt_rand(0,9999));
    }

}