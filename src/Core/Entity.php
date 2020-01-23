<?php


namespace Core;


use ReflectionClass;
use ReflectionException;

abstract class Entity
{
    protected ?int $id;

    public function __construct(array $data = null)
    {
        try
        {
            $this->hydrate($data);
        }
        catch (ReflectionException $e)
        {
            print_r($e->getMessage());
        }
    }

    //GETTER

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    //SETTER

    /**
     * @param int $id
     */
    public function setId(int $id = null): void
    {
        $this->id = $id;
    }

    //PUBLIC FUNCTION

    /**
     * Hydrates the instance
     * @param array $data
     * @throws ReflectionException
     */
    public function hydrate(array $data = null)
    {
        $reflection = new ReflectionClass($this);

        $attributes = $reflection->getProperties();

        //Gets the attribute and corresponding value
        foreach ($attributes as $attribute)
        {
            //Defines the setter name based on the attribute name
            $setter = $this->tableNameToSetter($attribute->name);

            isset($data[$attribute->name]) ? $value = $data[$attribute->name] : $value = null;

            //Calls the setter
            if(is_callable([$this,$setter]))
            {
                $this->$setter($value);
            }
        }
    }

    /**
     * Converts a multiwords database table name with format xxx_yyy into a setter function with format setXxxYyy
     * @param string $tableName
     * @return string
     */
    private function tableNameToSetter($tableName)
    {
        //Creates an array with every character as key.
        $arrTableName = str_split($tableName);

        //Checks each character
        foreach ($arrTableName as $key => $value)
        {
            //If the character is "underscore", changes next one into capital character
            if($value=="_" && $key < count($arrTableName)-1){
                $arrTableName[$key+1]=ucfirst($arrTableName[$key+1]);
            }
        }

        // Rebuilds the string and removes every underscore
        $formattedTableName = str_replace("_","",implode($arrTableName));

        //Returns the setter name
        return "set".ucfirst($formattedTableName);
    }
}