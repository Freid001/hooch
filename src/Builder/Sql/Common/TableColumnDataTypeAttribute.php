<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAttributeInterface;

/**
 * Class TableColumnDataTypeAttribute
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnDataTypeAttribute implements TableColumnDataTypeAttributeInterface
{
    /**
     * @var array
     */
    private $attribute = [];

    /**
     * TableColumnDataTypeAttribute constructor.
     * @param $columnName
     * @param $dataType
     * @param $type
     * @param $method
     */
    public function __construct($columnName, $dataType, $type, $method = null)
    {
        $this->attribute['column_name'] = $columnName;
        $this->attribute['data_type'] = $dataType;
        $this->attribute['type'] = $type;
        $this->attribute['method'] = $method;
    }

    /**
     * @param $parameter
     */
    public function parameter($parameter)
    {
        $this->attribute['parameter'][] = $parameter;
    }

    /**
     * @param $after
     */
    public function after($after)
    {
        $this->attribute['after'] = $after;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        if(isset($this->attribute[$key])){
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if(isset($this->attribute[$key])){
            return $this->attribute[$key];
        }
        return null;
    }

    /**
     * @return TableColumnDefinition
     */
    public function definition() : TableColumnDefinition
    {
        if(!isset($this->attribute['definition'])) {
            $this->attribute['definition'] = new TableColumnDefinition(
                $this->attribute['column_name'],
                $this->attribute['data_type'],
                $this->attribute['type']
            );
        }

        return $this->attribute['definition'];
    }
}