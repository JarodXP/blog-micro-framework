<?php


namespace Models;


use Core\Manager;
use Entities\Upload;
use PDO;
use PDOStatement;

class UploadManager extends Manager
{
    public const UPLOADS_TABLE = 'uploads',
        FILE_NAME = 'file_name',
        ORIGINAL_NAME = 'original_name',
        ALT = 'alt';

    /**
     * Gets a list of Upload with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::UPLOADS_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a upload in database
     * @param Upload $upload
     * @return bool
     */
    public function insertUpload(Upload $upload):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::UPLOADS_TABLE.'('.self::FILE_NAME.', '.self::ORIGINAL_NAME.', '.self::ALT.') 
                        VALUES(:fileName, :originalName, :alt)');

        $this->bindAllFields($q,$upload);

        return $q->execute();
    }

    /**
     * Gets a single upload using its id
     * @param int $uploadId
     * @return array
     */
    public function findUpload(int $uploadId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::UPLOADS_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $uploadId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given upload
     * @param Upload $upload
     * @return bool
     */
    public function updateUpload(Upload $upload)
    {
        $q = $this->dao->prepare('UPDATE '.self::UPLOADS_TABLE.' SET '
            .self::FILE_NAME.'= :fileName, '.self::ORIGINAL_NAME.' = :originalName, '
            .self::ALT.' = :alt WHERE id = :id');

        $q->bindValue(':id', $upload->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$upload);

        return $q->execute();
    }

    /**
     * Removes a specific upload
     * @param int $uploadId
     * @return bool
     */
    public function removeUpload(int $uploadId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::UPLOADS_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$uploadId,PDO::PARAM_INT);

        return $q->execute();
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param Upload $upload
     */
    private function bindAllFields(PDOStatement &$q, Upload $upload)
    {
        $q->bindValue(':fileName', $upload->getFileName());
        $q->bindValue(':originalName', $upload->getOriginalName());
        $q->bindValue(':alt', $upload->getAlt());
    }
}