<?php


namespace Services;


use Core\Entity;

trait PropertiesUpdater
{
    /**
     * Updates an existing entity with the new values
     * @param $fields
     * @param $entity
     */
    public function updateProperties(array $fields,Entity $entity):void
    {
        //Loops around every parameter sent
        foreach ($fields as $field => $value)
        {
            //Builds a setter name
            $setter = 'set'.ucfirst($field);

            //Checks if the setter exists for the class and calls it
            if(is_callable([$entity,$setter]))
            {
                $entity->$setter($value);
            }
        }
    }
}