<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;

/**
 * Class TableColumnDrop
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnDrop
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
     * TableColumnDrop constructor.
     * @param TableColumnHandlerInterface $table
     * @param bool|false $type
     */
    public function __construct(TableColumnHandlerInterface $table, $type = false)
    {
        $this->table = $table;
        $this->type = $type;
    }

    /**
     * Drop
     * @param $column
     */
    public function drop($column)
    {
        $this->table->handleColumn(new TableColumnDataTypeAttribute($column,null,$this->type), $this->type);
    }

    /**
     * Primary Key
     * @param array $columns
     */
    public function primaryKey($name = null)
    {
        $this->table->handlePrimaryKey($name,[]);
    }

    /**
     * Unique Key
     * @param $name
     * @param array $columns
     */
    public function uniqueKey($name)
    {
        $this->table->handleUniqueKey($name, []);
    }

    /**
     * Index
     * @param array $columns
     */
    public function index($name)
    {
        $this->table->handleIndex($name,[]);
    }
}