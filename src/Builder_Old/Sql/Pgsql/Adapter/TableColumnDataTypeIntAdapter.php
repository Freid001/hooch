<?php namespace freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataType;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataTypeAttribute;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAdapterInterface;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAttributeInterface;

/**
 * Class TableColumnDataTypeIntAdapter
 * @package freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter
 */
class TableColumnDataTypeIntAdapter implements TableColumnDataTypeAdapterInterface, TableColumnDataTypeAttributeInterface
{
    /**
     * @var TableColumnDataTypeAttribute
     */
    private $column;

    /**
     * @param TableColumnDataTypeAttribute $column
     * @return void
     */
    public function setTableColumnDataTypeAttribute(TableColumnDataTypeAttributeInterface $column)
    {
        $this->column = $column;
    }

    /**
     * @param $parameter
     */
    public function parameter($parameter){
        $this->column->parameter($parameter);
    }

    /**
     * @param $after
     */
    public function after($after){
        $this->column->after($after);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        $return = null;
        switch($key){
            case 'data_type':
                $return = true;
                break;
            case 'parameter':
                $return = false;
                break;
            case 'after':
                $return = false;
                break;
            default:
                $return = $this->column->hasAttribute($key);
        }

        return $return;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        $return = null;
        switch($key){
            case 'data_type':
                $return = TableColumnDataType::DATA_TYPE_INT;
                break;
            case 'parameter':
                $return = null;
                break;
            case 'after':
                $return = null;
                break;
            default:
                $return = $this->column->{$key};
        }

        return $return;
    }

    /**
     * @return TableColumnDefinition
     */
    public function definition() : TableColumnDefinition
    {
        return $this->column->definition();
    }
}