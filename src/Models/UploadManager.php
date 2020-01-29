<?php


namespace Models;


use Core\Manager;
use Entities\Upload;
use PDO;
use PDOStatement;

class UploadManager extends Manager
{
    public const TABLE = 'uploads',
        FILE_NAME = 'file_name',
        ORIGINAL_NAME = 'original_name',
        ALT = 'alt',
        TYPE = 'type';


    /**
     * Inserts a upload in database
     * @param Upload $upload
     * @return int
     */
    public function insertUpload(Upload $upload):int
    {
        //Checks if the file name and the original name are unique
        $this->checkUniqueFields([
            self::FILE_NAME => $upload->getFileName(),
            self::ORIGINAL_NAME => $upload->getOriginalName()
        ],false);

        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::FILE_NAME.', '.self::ORIGINAL_NAME.', '.self::ALT.', '.self::TYPE.') 
                        VALUES(:fileName, :originalName, :alt, :type)');

        $this->bindAllFields($q,$upload);

        $q->execute();

        return $this->dao->lastInsertId();
    }

    /**
     * Updates a given upload
     * @param Upload $upload
     * @return bool
     */
    public function updateUpload(Upload $upload)
    {
        //Checks if the file name and the original name are unique
        $this->checkUniqueFields([
            self::FILE_NAME => $upload->getFileName(),
            self::ORIGINAL_NAME => $upload->getOriginalName()
        ],true,$upload->getId());

        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::FILE_NAME.'= :fileName, '.self::ORIGINAL_NAME.' = :originalName, '
            .self::ALT.' = :alt, '.self::TYPE.' = :type WHERE id = :id');

        $q->bindValue(':id', $upload->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$upload);

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
        $q->bindValue(':type', $upload->getType());
        $q->bindValue(':alt', $upload->getAlt());
    }
}