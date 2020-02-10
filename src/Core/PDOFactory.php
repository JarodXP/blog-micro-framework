<?php


namespace Core;


use PDO;

abstract class PDOFactory
{
    /**
     * Creates a MySQL database connection and creates an instance of PDO
     * @return PDO Database Object
     */
    public static function createMySQLConnection(): PDO
    {
        $databaseInfo = $GLOBALS['dbConfig'];

        $dao = new PDO('mysql:host='.$databaseInfo['DATABASE_HOST'].';port='.$databaseInfo['DATABASE_PORT'].';dbname='
            .$databaseInfo['DATABASE_NAME'],
            $databaseInfo['DATABASE_USERNAME'],$databaseInfo['DATABASE_PASSWORD'],
            array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $dao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dao;
    }
}