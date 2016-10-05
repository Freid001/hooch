<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataTypeAttribute;

/**
 * Interface TableColumnDataTypeAdapterInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnDataTypeAdapterInterface
{
    /**
     * @param TableColumnDataTypeAttribute $table
     * @return mixed
     */
    public function setTableColumnDataTypeAttribute(TableColumnDataTypeAttribute $table);
}