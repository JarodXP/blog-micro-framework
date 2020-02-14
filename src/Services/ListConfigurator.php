<?php


namespace Services;



use Core\Manager;
use Exceptions\ListException;

class ListConfigurator
{
    protected ?string $orderBy;
    protected ?string $limit;
    protected ?string $offset;

    protected Manager $manager;

    public const DIRECTION_ASC = 'asc',
        DIRECTION_DESC = 'desc',
        LIMIT = 'limit',
        PAGE = 'page',
        OFFSET = 'offset',
        ORDER = 'order',
        CURRENT_ORDER = 'currentOrder',
        DIRECTION = 'direction';

    //PUBLIC FUNCTIONS//////////////////

    /**
     * ListConfigurator constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Builds a string requestParameters to be used in Manager::findBy() method
     * @param array|null
     * Expected format ['filterColumn' => value, ...]
     * @param array|null $requestOptions
     * Expected keys (all are optional) ['limit','offset','order','direction']
     * @return string
     */
    public function getRequestParameters(array $requestConditions = null, array $requestOptions = null)
    {
        $requestParameters = '';

        //Sets where clause of the request
        if(!is_null($requestConditions))
        {
            $requestParameters .= $this->defineRequestConditions($requestConditions);
        }

        //Sets limit, offset, order and direction clauses of the request
        if(!is_null($requestOptions))
        {
            $requestParameters .= $this->defineRequestOptions($requestOptions);
        }

        return $requestParameters;
    }

    //PRIVATE FUNCTIONS/////////////////////////////////////////////////////////////

    /**
     * Hydrates the instance
     * @param array $data
     */
    private function hydrateRequestOptions(array $data):void
    {
        $expectedOptions = ['limit','offset','order','direction'];

        foreach ($expectedOptions as $option)
        {
            //Defines the setter name based on the option name
            $setter = 'set'.ucfirst($option);

            //Defines the value
            isset($data[$option]) ? $value = $data[$option] : $value = null;

            //Calls the setter
            $this->$setter($value);
        }
    }

    /**
     * Builds the string with LIMIT, OFFSET, ORDER and DIRECTION clause for SQL request
     * @param array $options
     * @return string
     */
    private function defineRequestOptions(array $options):string
    {
        $this->hydrateRequestOptions($options);

        //Builds the string for the $requestOptions
        return $this->orderBy.' '.$this->limit.' '.$this->offset;
    }

    /**
     * Builds the string WHERE clause for SQL request
     * @param array|null $conditions
     * @return string
     */
    private function defineRequestConditions(array $conditions):string
    {
        //Starts the string with WHERE tag
        $requestConditions = 'WHERE ';

        //Adds a pair of column / value for each WHERE parameter
        foreach ($conditions as $column => $value)
        {
            if(is_string($value))
            {
                $value = '"'.$value.'"';
            }
            $requestConditions .= $column.' = '.$value;

            //Adds AND clause between each parameter
            !($column == array_key_last($conditions))
                ? $requestConditions .= ' AND '
                : $requestConditions .=' ';
        }

        return $requestConditions;
    }

    //SETTERS//////////////////////////

    /**
     * Sets the number of items (SQL LIMIT) requested
     * @param int|null $limit
     */
    protected function setLimit(int $limit = null): void
    {
        //Sanitizes parameter to avoid overwhelming limit.
        if($limit > 100)
        {
            $limit = 100;
        }

        //Sets the $limit string
        $limit == null ? $this->limit = '' : $this->limit = 'LIMIT '.$limit;
    }

    /**
     * Sets the start item (SQL OFFSET) of the request
     * @param int|null $offset
     */
    protected function setOffset(int $offset = null): void
    {
        //Sets the $offset string
        $offset == null ? $this->offset = '' : $this->offset = 'OFFSET '.$offset;
    }

    /**
     * Sets the sort column (SQL ORDER BY)
     * @param string $order
     */
    protected function setOrder(string $order = null): void
    {
        $order == null ? $this->orderBy = ''.$order : $this->orderBy = 'ORDER BY '.$order;
    }

    /**
     * Sets the direction for SQL ORDER BY clause of the request
     * @param string $direction
     */
    protected function setDirection(string $direction = null): void
    {
        //Checks if direction is valid
        if (!is_null($direction) && strtolower($direction) != self::DIRECTION_DESC &&
            strtolower($direction) != self::DIRECTION_ASC)
        {
            throw new ListException('Direction parameter is not valid');
        }
        else
        {
            $this->orderBy .= ' '.$direction;
        }
    }
}



