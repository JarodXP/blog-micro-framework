<?php


namespace Core;


use Exceptions\EntityAttributeException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

abstract class Entity
{
    protected ?int $id;

    protected ?array $mandatoryProperties;

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

        $this->setMandatoryProperties();
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

    /**
     * Sets an array with the mandatory fields
     */
    protected abstract function setMandatoryProperties();

    //PUBLIC FUNCTIONS

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
     * Checks if the mandatory properties are not null before insert or update
     * $this->mandatoryProperties array is to be set before the method is called.
     * If not, returns true
     * @return mixed
     */
    public function isValid()
    {
        //Checks each of the mandatory property if it's unset or null
        foreach ($this->mandatoryProperties as $property)
        {
            //In case of unset or null, returns false
            if (!isset($this->$property) || is_null($this->$property))
            {
                throw new EntityAttributeException('Le champs '.$property.' ne peut être vide');
            }
        }

        return true;
    }

    /**
     * Updates an existing entity with the new values
     * @param $fields
     * @param $entity
     */
    public function updateProperties(array $fields):void
    {
        //Loops around every parameter sent
        foreach ($fields as $field => $value)
        {
            //Builds a setter name
            $setter = 'set'.ucfirst($field);

            //Checks if the setter exists for the class and calls it
            if(is_callable([$this,$setter]))
            {
                $this->$setter($value);
            }
        }
    }

    //PRIVATE FUNCTIONS

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