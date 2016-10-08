<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataTypeAttribute;

/**
 * Interface TableColumnInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnHandlerInterface
{
    /**
     * Handle Modify
     * @param $column
     */
    public function handleModify($column);

    /**
     * Handle Column
     * @param TableColumnDataTypeAttribute $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDataTypeAttribute $column, $type = null);

    /**
     * Handle Primary Key
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handlePrimaryKey($name,array $columns);

    /**
     * Handle Unique Key
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handleUniqueKey($name,array $columns);

    /**
     * Handle Index
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handleIndex($name,array $columns);
}
