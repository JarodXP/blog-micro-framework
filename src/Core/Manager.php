<?php


namespace Core;


use Exceptions\EntityAttributeException;
use PDO;
use Services\ListConfigurator;

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
     * @param array $conditions
     * @param array $options
     * @return array
     */
    public function findListBy(array $conditions = null, array $options = null):array
    {
        $listConfigurator = new ListConfigurator($this);

        $requestParameters = $listConfigurator->getRequestParameters($conditions,$options);

        $q = $this->dao->prepare('SELECT * FROM '.static::TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets a single element using a WHERE clause condition
     * @param array $conditions
     * @return mixed
     */
    public function findOneBy(array $conditions)
    {
        if(!empty($this->findListBy($conditions)))
        {
            //If not empty, returns first index
            return $this->findListBy($conditions)[0];
        }
        else
        {
            //If empty, returns empty array
            return $this->findListBy($conditions);
        }
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
     * Checks if the requested fields are unique
     * @param array $fieldValue
     * @param bool $update
     * @param int|null $id
     * @throws EntityAttributeException
     */
    public function checkUniqueFields(array $fieldValue, bool $update, int $id = null)
    {
        foreach ($fieldValue as $field => $value)
        {
            //Looks for elements with the same criteria
            $existing = $this->findOneBy([$field => $value]);

            //If other element exists
            if(!empty($existing))
            {
                //Checks if it's not the same in case of update
                if(($update == true && (int)$existing['id'] != $id) || $update == false)
                {
                    //If not throws exception
                    throw new EntityAttributeException('La valeur '.$value.' existe déjà.');
                }
            }
        }
    }

}