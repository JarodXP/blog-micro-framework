<?php


namespace Core;


use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

abstract class Entity
{
    protected ?int $id;

    public const SETTER = 1, PROPERTY = 2;


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
        //Converts the data arrays keys into properties names
        if(!is_null($data))
        {
            foreach ($data as $column => $value)
            {
                //Sets a new key formatted as an Entity property name
                $property = $this->tableNameConverter(self::PROPERTY,$column);

                //Unsets the old key
                unset($data[$column]);

                //Sets the new pair key / value
                $data[$property] = $value;
            }
        }

        $reflection = new ReflectionClass($this);

        $attributes = $reflection->getProperties();

        //Gets the attribute and corresponding value
        foreach ($attributes as $attribute)
        {
            //Defines the setter name based on the attribute name
            $setter = $this->tableNameConverter(self::SETTER,$attribute->name);

            //Defines the database column name based on the attribute name
            $column = $this->tableNameConverter(self::PROPERTY,$attribute->name);

            isset($data[$column]) ? $value = $data[$column] : $value = null;

            //Calls the setter
            if(is_callable([$this,$setter]))
            {
                $this->$setter($value);
            }
        }
    }

    /**
     * Converts a multiwords database table name with format xxx_yyy into a setter function with format setXxxYyy
     * @param int $stringType
     * @param string $tableName
     * @return string
     */
    private function tableNameConverter(int $stringType, string $tableName):string
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

        //Returns either the setter name or the property name
        if($stringType == self::SETTER)
        {
            return "set".ucfirst($formattedTableName);
        }
        elseif($stringType == self::PROPERTY)
        {
            return $formattedTableName;
        }
        else
        {
            throw new InvalidArgumentException('String type is not valid');
        }
    }
}