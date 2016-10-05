<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeInterface;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;

/**
 * Class TableColumnDataType
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnDataType implements TableColumnDataTypeInterface
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
     * @var TableColumnHandlerInterface
     */
    private $table;

    /**
     * @var string
     */
    private $type;

    /**
     * TableColumnDataType constructor.
     * @param TableColumnHandlerInterface $table
     * @param $column
     * @param bool|false $type
     */
    public function __construct(TableColumnHandlerInterface $table, $column, $type = false)
    {
        $this->table = $table;
        $this->column = $column;
        $this->type = $type;
    }

    /**
     * Boolean
     * @return TableColumnDefinitionInterface
     */
    public function boolean() : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_BOOLEAN, $this->type,'boolean');

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
    }

    /**
     * Decimal
     * @param $precision
     * @param $scale
     * @return TableColumnDefinitionInterface
     */
    public function decimal($precision, $scale) : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_DECIMAL, $this->type,'decimal');
        $column->parameter($precision);
        $column->parameter($scale);

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
    }

    /**
     * Int
     * @param $length
     * @return TableColumnDefinitionInterface
     */
    public function int($length = 11) : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_INT, $this->type,'int');
        $column->parameter($length);

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
    }

    /**
     * Increment
     * @param $length
     * @return TableColumnDefinitionInterface
     */
    public function increment($length = 11)
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_INT, $this->type,'increment');
        $column->parameter($length);
        $column->after(self::AUTO_INCREMENT);

        $this->table->handleColumn($column,$this->type);
    }

    /**
     * Text
     * @return TableColumnDefinitionInterface
     */
    public function text() : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_TEXT, $this->type,'text');

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
    }

    /**
     * Varchar
     * @param int $length
     * @return TableColumnDefinitionInterface
     */
    public function varchar($length = 225) : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_VARCHAR, $this->type,'varchar');
        $column->parameter($length);

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
    }
}