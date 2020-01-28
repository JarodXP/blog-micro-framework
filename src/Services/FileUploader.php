<?php


namespace Services;


use Core\File;
use Entities\Upload;
use Exceptions\EntityAttributeException;
use Exceptions\UploadException;
use Models\UploadManager;

/**
 * Trait FileUploader
 * @package Services
 */
trait FileUploader
{
    protected File $file;

    /**
     * Uploads an image and creates an Upload object
     * @param string $formFileName
     * @param string $alt
     * @return Upload
     */
    public function uploadImage(string $formFileName, string $alt):Upload
    {
        //Creates an instance of File to handle the file info
        $this->file = new File($_FILES[$formFileName]);

        //Creates an Upload instance corresponding to the image
        $image = new Upload([
            'fileName' => $this->file->getFileName(),
            'originalName' => $this->file->getOriginalName(),
            'type' => $this->file->getMimeType(),
            'alt' => $alt
        ]);

        //Checks if the OriginalName does not already exist
        $uploadManager = new UploadManager();

        $uploadManager->checkUniqueFields([UploadManager::ORIGINAL_NAME => $this->file->getOriginalName()],false);

        // Gets the image type and prevents from an unauthorized one (PNG or JPEG only)
        $imageType = exif_imagetype($this->file->getTempFile());

        //Creates a copy of the file image in the public directory to avoid images scripts threats
        $imageConverter = new GDImageConverter($imageType);

        $imageConverter->copyUploadImage($this->file->getTempFile(),
            $GLOBALS['uploadDirectory'].'\\'.$this->file->getFileName());

        //registers the Upload object in database
        try
        {
            $image->isValid();
        }
        catch (EntityAttributeException $e)
        {
            //If Upload is not valid, removes temporary and newly created files
            unlink($this->file->getTempFile());

            unlink($GLOBALS['uploadDirectory'].'\\'.$this->file->getFileName());

            //Throws another type of exception to be handled by the Controller
            throw new UploadException($e->getMessage());
        }

        $manager = new UploadManager();

        $image->setId($manager->insertUpload($image));

        return $image;
    }

    /**
     * Removes the file and the corresponding upload
     * @param int $id
     * @throws UploadException
     */
    public function removeFile(int $id)
    {
        $manager = new UploadManager();

        //Gets the upload object corresponding to the file to be removed
        $fileData= $manager->findOneBy(['id' => $id]);

        $uploadToRemove = new Upload($fileData);

        //Gets the file name
        $fileName = $uploadToRemove->getFileName();

        //Removes file and corresponding upload
        if(unlink($GLOBALS['uploadDirectory'].'\\'.$fileName))
        {

            $manager->removeElement($id);
        }
        else
        {
            throw new UploadException('The file couldn\'t be removed');
        }
    }
}