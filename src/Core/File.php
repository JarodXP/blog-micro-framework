<?php


namespace Core;




use Exceptions\UploadException;

class File
{
    private string $_fileName, $_originalName, $_tempFile, $_mimeType;

    private int $_size;

    public function __construct(array $file)
    {
        //Checks if no upload errors occurred && if file _size doesn't exceeds allowed
        if($this->checkErrorCode($file['error']) && $this->checkFileSize($file['size'],$file['type']))
        {
            //Sets the files properties
            $this->_originalName = $file['name'];

            $this->_tempFile = $file['tmp_name'];

            $this->_size = $file['size'];

            $this->_mimeType = $file['type'];

            $this->setFileName();
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
        switch ($errorCode)
        {
            case 0: return true;

            case 1;

            case 2:  throw new UploadException('La taille du fichier ne doit pas dépasser 300 ko');

            default:
                error_log('Upload file error. $_FILES[error] = '.$errorCode);

                throw new UploadException('Une erreur est survenue lors du téléchargement du fichier.');
        }
    }

    /**
     * Checks the file _size and throws an exception in case of problem
     * @param int $size
     * @param string $type
     * @return bool
     */
    private function checkFileSize(int $size, string $type):bool
    {
        //Sets a variable for the maximum allowed file size
        if(strpos($type,'image') !== false)
        {
            $maxSize = $GLOBALS['config']['MAX_SIZE_IMAGE'];
        }
        else
        {
            $maxSize = $GLOBALS['config']['MAX_SIZE_PDF'];
        }

        //Checks if file exceeds maxSize
        if($size > $maxSize)
        {
            throw new UploadException('Le fichier ne doit pas dépasser '.($maxSize / 1000).' ko');
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