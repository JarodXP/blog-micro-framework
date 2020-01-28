<?php


namespace Core;


use ArrayAccess;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

abstract class Entity implements ArrayAccess
{
    protected ?int $id;

    public const SETTER = 1, PROPERTY = 2;//Used in the method tableNameConverter


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

    //ARRAY ACCESS FUNCTIONS

    public function offsetGet($var)
    {
        $getter = 'get'.ucfirst($var);

        if(isset($this->$var) && is_callable([$this,$getter]))
        {
            return $this->$getter();
        }

        return null;
    }

    public function offsetSet($var, $value)
    {
        $setter = 'set'.ucfirst($var);

        if(isset($var) && is_callable([$this,$setter]))
        {
            $this->$setter($value);
        }
    }

    public function offsetUnset($var):void
    {
        throw new InvalidArgumentException('Removing indexes is not possible for this class');
    }

    public function offsetExists($var):bool
    {
        return isset($this->$var) && is_callable([$this,$var]);
    }
}