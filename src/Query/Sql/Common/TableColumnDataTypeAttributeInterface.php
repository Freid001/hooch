<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;

/**
 * Interface TableColumnDataTypeAttributeInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnDataTypeAttributeInterface
{
    /**
     * @param $parameter
     */
    public function parameter($parameter);

    /**
     * @param $after
     */
    public function after($after);

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key);

    /**
     * @param $key
     * @return null
     */
    public function __get($key);

    /**
     * @return TableColumnDefinition
     */
    public function definition() : TableColumnDefinition;
}