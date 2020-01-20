<?php


namespace Core;


class Entity
{
    protected int $id;

    public function __construct(array $data = null)
    {
        if(!is_null($data))
        {
            $this->hydrate($data);
        }
    }

    //GETTER

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    //SETTER

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    //PUBLIC FUNCTION

    /**
     * Hydrates the instance
     * @param array $data
     */
    public function hydrate(array $data)
    {
        var_dump($data);
        //Gets the attribute and corresponding value
        foreach ($data as $attribute=>$value)
        {
            //Defines the setter name based on the attribute name
            $setter = $this->tableNameToSetter($attribute);

            //Calls the setter
            if(is_callable([$this,$setter]))
            {
                $this->$setter($value);
            }
        }

        var_dump($this);
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