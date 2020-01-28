<?php


namespace Services;



use Exceptions\UploadException;

class GDImageConverter
{
    protected string $createResourceFunction,
        $createImageFunction;

    public function __construct(int $imageType)
    {
        $this->setGDImageFunctions($imageType);
    }

    /**
     * Creates a copy of the uploaded image into the upload directory
     * @param string $tempFileName
     * @param string $destFileName
     */
    public function copyUploadImage(string $tempFileName, string $destFileName)
    {
        //Dynamic function name
        $resourceFunction = $this->createResourceFunction;

        //Creates a GD image resource from the file
        $tempImage = $resourceFunction($tempFileName);

        $imageSize = getimagesize($tempFileName);

        //Resizes image at 99% of their original _size to completely restructure file and avoid malicious code
        $destImageWidth = $imageSize[0]*0.99;
        $destImageHeight = $imageSize[1]*0.99;

        //Creates a blank image resource for destImage
        $destImage = imagecreatetruecolor($destImageWidth,$destImageHeight);

        //Preserves transparency
        imagecolortransparent($destImage, imagecolorallocatealpha($destImage, 0, 0, 0, 127));
        imagealphablending($destImage, false);
        imagesavealpha($destImage, true);

        //Copies and resizes image resources
        if(!imagecopyresampled($destImage,$tempImage,0,0,0,0,
            $destImageWidth,$destImageHeight,$imageSize[0],$imageSize[1]))
        {
            error_log('The image couldn\'t be copied');

            throw new UploadException('Un problème est survenu lors du téléchargement');
        }

        //Dynamic name for creating image function
        $imageFunction = $this->createImageFunction;

        //Writes either a PNG or JPEG image in the file
        if(!$imageFunction($destImage,$destFileName))
        {
            error_log('The file couldn\'t be written');

            throw new UploadException('Un problème est survenu lors du téléchargement');
        }
    }

    /**
     * Sets dynamic names for images functions
     * @param $imageType
     */
    private function setGDImageFunctions(int $imageType):void
    {
        //Creates a GD image resource from the file
        switch ($imageType)
        {
            case IMAGETYPE_PNG:
                $this->createResourceFunction = 'imagecreatefrompng';
                $this->createImageFunction = 'imagepng';
                break;

            case IMAGETYPE_JPEG:
                $this->createResourceFunction = 'imagecreatefromjpeg';
                $this->createImageFunction = 'imagejpeg';
                break;

            default :
                throw new UploadException('Seuls les fichiers PNG et JPEG sont autorisés');
        }
    }
}