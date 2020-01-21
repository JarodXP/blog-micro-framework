<?php


namespace Core;


use PDO;

abstract class Manager
{
    protected PDO $dao;

    protected string $table;

    public function __construct()
    {
        //Sets the database object
        $this->dao = PDOFactory::createMySQLConnection();
    }

    /**
     * Gets a list of elements with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.static::TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets a single element by its id
     * @param int $elementId
     * @return array
     */
    public function findById(int $elementId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.static::TABLE.' WHERE id = :id');

        $q->bindValue(':id', $elementId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Removes a specific element
     * @param int $elementId
     * @return bool
     */
    public function removeElement(int $elementId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.static::TABLE.' WHERE id = :id');

        $q->bindValue(':id',$elementId,PDO::PARAM_INT);

        return $q->execute();
    }

    /**
     * Gets the last inserted id
     * @return int
     */
    public function lastId():int
    {
        return $this->dao->lastInsertId();
    }

    /**
     * Gets the columns for a requested table.
     * Used to check if column exists.
     * @param string $table
     * @return array
     */
    public function databaseTableColumns(string $table = null)
    {
        $q = $this->dao->prepare('SELECT COLUMN_NAME FROM information_schema. COLUMNS WHERE TABLE_NAME = :table');

        $q->execute([':table' => $table]);

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

}