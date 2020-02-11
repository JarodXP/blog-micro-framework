<?php


namespace Services;


use Core\File;
use Entities\Upload;
use Exception;
use Exceptions\EntityAttributeException;
use Exceptions\UploadException;
use Models\UploadManager;
use PDOException;

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
        try
        {
            //Creates an instance of Upload
            $image = $this->createUploadObject($formFileName, $alt);

            // Gets the image type and prevents from an unauthorized one (PNG or JPEG only)
            $imageType = exif_imagetype($this->file->getTempFile());

            //Creates a copy of the file image in the public directory to avoid images scripts threats
            $imageConverter = new GDImageConverter($imageType);

            $imageConverter->copyUploadImage($this->file->getTempFile(),
                $GLOBALS['uploadDirectory'].'/'.$this->file->getFileName());

            //registers the Upload object in database
            $image->isValid();

            return $this->insertUploadObject($image);
        }
        catch (PDOException | EntityAttributeException $e)
        {
            $this->uploadExceptionProcess($e);
        }
    }

    /**
     * Uploads an image and creates an Upload object
     * @param $formFileName
     * @return Upload
     */
    public function uploadPDF($formFileName):Upload
    {
        try
        {
            //Creates an instance of Upload
            $pdfUpload = $this->createUploadObject($formFileName);

            //Checks the file type
            if(mime_content_type($this->file->getTempFile()) != 'application/pdf')
            {
                throw new UploadException('Seuls les fichiers PDF sont autorisés');
            }

            //Copies the file in the upload directory
            rename($this->file->getTempFile(),$GLOBALS['uploadDirectory'].'/'.$this->file->getFileName());

            //registers the Upload object in database
            $pdfUpload->isValid();

            return $this->insertUploadObject($pdfUpload);
        }
        catch (PDOException | UploadException | EntityAttributeException $e)
        {
            $this->uploadExceptionProcess($e);
        }
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
        if(unlink($GLOBALS['uploadDirectory'].'/'.$fileName))
        {

            $manager->removeElement($id);
        }
        else
        {
            throw new UploadException('The file couldn\'t be removed');
        }
    }

    /**
     * Creates an Upload objects from the File property
     * @param string $formFileName
     * @param null $alt
     * @return Upload
     */
    private function createUploadObject(string $formFileName, $alt = null):Upload
    {
        //Creates an instance of File to handle the file info
        $this->file = new File($_FILES[$formFileName]);

        //Checks if the OriginalName does not already exist
        $uploadManager = new UploadManager();

        $uploadManager->checkUniqueFields([UploadManager::ORIGINAL_NAME => $this->file->getOriginalName()],false);

        //Creates an Upload instance corresponding to the image
        return new Upload([
            'fileName' => $this->file->getFileName(),
            'originalName' => $this->file->getOriginalName(),
            'type' => $this->file->getMimeType(),
            'alt' => $alt
        ]);
    }

    /**
     * Inserts the Upload object and sets its id
     * @param Upload $uploadObject
     * @return Upload
     */
    private function insertUploadObject(Upload $uploadObject):Upload
    {
        $manager = new UploadManager();

        //Inserts in database and sets the id
        $uploadObject->setId($manager->insertUpload($uploadObject));

        return $uploadObject;
    }

    /**
     * Unlink files in case of exception and throws a new Upload Exception
     * @param Exception $e
     * @throws UploadException
     */
    private function uploadExceptionProcess(Exception $e)
    {
        //If Upload is not valid, removes temporary
        unlink($this->file->getTempFile());

        //and newly created files if exists
        if(file_exists($GLOBALS['uploadDirectory'].'\\'.$this->file->getFileName()))
        {
            unlink($GLOBALS['uploadDirectory'].'\\'.$this->file->getFileName());
        }

        //Throws another type of exception to be handled by the Controller
        throw new UploadException($e->getMessage());
    }
}