<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;

/**
 * Interface TableColumnDataTypeInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnDataTypeInterface
{
    const DATA_TYPE_BIG_INT = 'BIGINT';
    const DATA_TYPE_BLOB = 'BLOB';
    const DATA_TYPE_BOOLEAN = 'BOOL';
    const DATA_TYPE_CHAR = 'CHAR';
    const DATA_TYPE_DATE = 'DATE';
    const DATA_TYPE_DATETIME = 'DATETIME';
    const DATA_TYPE_DECIMAL = 'DECIMAL';
    const DATA_TYPE_DOUBLE = 'DOUBLE';
    const DATA_TYPE_ENUM = 'ENUM';
    const DATA_TYPE_FLOAT = 'FLOAT';
    const DATA_TYPE_INT = 'INT';
    const DATA_TYPE_INTEGER = 'INTEGER';
    const DATA_TYPE_LONGTEXT = 'LONGTEXT';
    const DATA_TYPE_MEDIUM_INTEGER = 'MEDIUMINT';
    const DATA_TYPE_MEDIUM_TEXT = 'MEDIUMTEXT';
    const DATA_TYPE_SMALL_INTEGER = 'SMALLINT';
    const DATA_TYPE_TEXT= 'TEXT';
    const DATA_TYPE_TIME= 'TIME';
    const DATA_TYPE_TINY_INTEGER = 'TINYINT';
    const DATA_TYPE_TINY_TIMESTAMP = 'TIMESTAMP';
    const DATA_TYPE_UUID = 'UUID';
    const DATA_TYPE_VARCHAR = 'VARCHAR';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';

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
     * Increment
     * @param $length
     * @return TableColumnDefinitionInterface
     */
    public function increment($length = 11) : TableColumnDefinitionInterface;

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