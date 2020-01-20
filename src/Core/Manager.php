<?php


namespace Core;


use PDO;

abstract class Manager
{
    protected PDO $dao;

    public function __construct()
    {
        //Sets the database object
        $this->dao = PDOFactory::createMySQLConnection();
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

    public abstract function findListOf(string $requestParameters);

}