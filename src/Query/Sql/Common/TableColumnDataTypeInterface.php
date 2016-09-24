<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;

/**
 * Interface TableColumnDataTypeInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnDataTypeInterface
{

    /**
     * Decimal
     * @param $precision
     * @param $scale
     * @return TableColumnDefinitionInterface
     */
    public function decimal($precision, $scale) : TableColumnDefinitionInterface;

    /**
     * Int
     * @param $length
     * @return TableColumnDefinitionInterface
     */
    public function int($length = 11) : TableColumnDefinitionInterface;

    /**
     * Text
     * @return TableColumnDefinitionInterface
     */
    public function text() : TableColumnDefinitionInterface;

    /**
     * Varchar
     * @param int $length
     * @return TableColumnDefinitionInterface
     */
    public function varchar($length = 225) : TableColumnDefinitionInterface;
}