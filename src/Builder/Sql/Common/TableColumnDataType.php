<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeInterface;

/**
 * Class TableColumnDataType
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnDataType implements TableColumnDataTypeInterface
{
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
    public function increment($length = 11) : TableColumnDefinitionInterface
    {
        $column = new TableColumnDataTypeAttribute($this->column, self::DATA_TYPE_INT, $this->type,'increment');
        $column->parameter($length);
        $column->after(self::AUTO_INCREMENT);

        $this->table->handleColumn($column,$this->type);

        return $column->definition();
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