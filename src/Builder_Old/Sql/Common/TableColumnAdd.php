<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;

/**
 * Class TableColumnAdd
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnAdd
{
    /**
     * @var TableColumnHandlerInterface
     */
    private $table;

    /**
     * @var bool
     */
    private $type;

    /**
     * TableColumnModify constructor.
     */
    public function __construct(TableColumnHandlerInterface $table, $type = false)
    {
        $this->table = $table;
        $this->type = $type;
    }

    /**
     * Modify
     * @param $column
     * @return TableColumnDataType
     */
    public function add($column)
    {
        $this->table->handleModify($column);
        return new TableColumnDataType($this->table, $column, $this->type);
    }

    /**
     * Primary Key
     * @param $name
     * @param array $columns
     */
    public function primaryKey($name,array $columns)
    {
        $this->table->handlePrimaryKey($name,$columns);
    }

    /**
     * Unique Key
     * @param $name
     * @param array $columns
     */
    public function uniqueKey($name,array $columns)
    {
        $this->table->handleUniqueKey($name, $columns);
    }

    /**
     * Index
     * @param $name
     * @param array $columns
     */
    public function index($name,array $columns)
    {
        $this->table->handleIndex($name,$columns);
    }
}