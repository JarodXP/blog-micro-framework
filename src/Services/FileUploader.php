<?php


namespace Services;


use Core\File;
use Entities\Upload;
use Models\UploadManager;

trait FileUploader
{
    protected File $file;

    /**
     * Register an uploaded image in database
     * @param string $formFileName
     * @param string $alt
     * @param bool $update
     * @param int|null $uploadId
     * @return Upload
     */
    public function registerImage(string $formFileName, string $alt, int $uploadId = null):Upload
    {
        $image = $this->uploadImage($formFileName, $alt);

        //registers the Upload object in database
        $manager = new UploadManager();

        //Either inserts or updates the image in database
        if($image->isValid())
        {
            if(is_null($uploadId))
            {
                //Inserts the image in database and sets the id
                $image->setId($manager->insertUpload($image));
            }
            else
            {
                //Updates the image in database
                $manager->updateUpload($image);
            }
        }

        return $image;
    }

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

        // Gets the image type and prevents from an unauthorized one (PNG or JPEG only)
        $imageType = exif_imagetype($this->file->getTempFile());

        //Creates a copy of the file image in the public directory to avoid images scripts threats
        $imageConverter = new GDImageConverter($imageType);

        $imageConverter->copyUploadImage($this->file->getTempFile(),
            $GLOBALS['uploadDirectory'].'\\'.$this->file->getFileName());

        return $image;
    }
}