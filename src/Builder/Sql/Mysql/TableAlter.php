<?php namespace freidcreations\QueryMule\Builder\Sql\Mysql;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDrop;
use freidcreations\QueryMule\Query\Sql\Common\QueryBuilderInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAttributeInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;
use freidcreations\QueryMule\Query\Sql\Common\HasAccent;
use freidcreations\QueryMule\Query\Sql\Common\HasTableColumn;
use freidcreations\QueryMule\Query\Sql\Common\HasBuilder;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnModify;

/**
 * Class TableAlter
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableAlter implements QueryBuilderInterface, TableColumnHandlerInterface
{
    use HasAccent;
    use HasTableColumn;
    use HasBuilder;

    const ADD_COLUMN = 'ADD COLUMN';
    const CHANGE = 'CHANGE';
    const MODIFY_COLUMN = 'MODIFY COLUMN';
    const DROP_COLUMN = 'DROP COLUMN';

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array
     */
    private $primaryKeys = [];

    /**
     * @var array
     */
    private $uniqueKeys = [];

    /**
     * @var array
     */
    private $indexs = [];

    /**
     * @var array
     */
    private $modify = null;

    /**
     * @var Table
     */
    private $table;

    /**
     * TableCreate constructor.
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->makeBuilder($this);
        $this->makeAccent($this);
        $this->makeTableColumn($this);
    }

    /**
     * Make
     * @param Table $table
     * @return self
     */
    public static function make(Table $table)
    {
        return new self($table);
    }

    /**
     * Table
     * @return Table
     */
    public function table() : Table
    {
        return $this->table;
    }

    /**
     * Alter
     * @return $this
     */
    public function alter()
    {
        Sql::raw(self::ALTER_TABLE)->add(self::ALTER_TABLE . ' ' . $this->accent($this->table->name()));
        return $this;
    }

    /**
     * Add
     * @param \Closure $columns
     * @return $this
     */
    public function add(\Closure $columns)
    {
        //Set columns
        $columns(new TableColumnAdd($this,'add'));

        //Add comer if needed
        if(isset($this->columns['modify']) || isset($this->columns['drop'])){
            sql::raw(self::ALTER_TABLE)->add(', ');
        }

        //Add columns
        if(isset($this->columns['add'])) {
            $parameters = $this->generateColumns(self::ALTER_TABLE, $this->columns['add'], [
                'null',
                'not_null',
                'default',
                'comment',
                'first',
                'after'
            ], [], function($key,$column){
                return self::ADD_COLUMN . " " . $this->accent($column->column_name);
            });

            //Add parameters
            sql::raw(self::ALTER_TABLE)->add(null, $parameters);
        }

        $append = !empty($this->columns['add']) ? true : false;

        $this->generateConstraint(self::ALTER_TABLE, $this->primaryKeys, $append, function($name,$column){
            return self::ADD . " " . TableColumnDefinitionInterface::PRIMARY_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->uniqueKeys, $append, function($name,$column){
            return self::ADD . " " . TableColumnDefinitionInterface::UNIQUE_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->indexs, $append, function($name,$column){
            return self::ADD . " " . TableColumnDefinitionInterface::INDEX . " " . $this->accent($name) . " (" . $column . ")";
        });

        return $this;
    }

    /**
     * Modify
     * @param \Closure $columns
     * @return $this
     */
    public function modify(\Closure $columns)
    {
        //Set columns
        $columns(new TableColumnModify($this, 'modify'));

        //Add comer if needed
        if(isset($this->columns['add']) || isset($this->columns['drop'])){
            sql::raw(self::ALTER_TABLE)->add(', ');
        }

        //Modify columns
        if(isset($this->columns['modify'])) {

            //Generate columns
            $parameters = $this->generateColumns(self::ALTER_TABLE, $this->columns['modify'], [
                'null',
                'not_null',
                'default',
                'comment',
                'first',
                'after',
            ], [], function($key,$column){
                if(!isset($this->modify[$key]) || ($this->modify[$key] != $column->column_name)) {
                    return self::CHANGE . " " . $this->accent($this->modify[$key]) . " " . $this->accent($column->column_name);
                }

                return self::MODIFY_COLUMN . " " . $this->accent($column->column_name);
            });

            //Add parameters
            sql::raw(self::ALTER_TABLE)->add(null, $parameters);
        }

        $append = (!empty($this->columns['add']) || !empty($this->columns['modify'])) ? true : false;

        $this->generateConstraint(self::ALTER_TABLE, $this->primaryKeys, $append, function($name,$column){
            return self::DROP . " " .  TableColumnDefinitionInterface::PRIMARY_KEY . ", " .
                   self::ADD . " " . TableColumnDefinitionInterface::PRIMARY_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->uniqueKeys, $append, function($name,$column){
            return self::DROP . " " .  TableColumnDefinitionInterface::INDEX . " " . $this->accent($name) . ", " .
                   self::ADD . " " . TableColumnDefinitionInterface::UNIQUE_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->indexs, $append, function($name,$column){
            return self::DROP . " " .  TableColumnDefinitionInterface::INDEX . " " . $this->accent($name) . ", " .
                   self::ADD . " " . TableColumnDefinitionInterface::INDEX . " " . $this->accent($name) . " (" . $column . ")";
        });

        return $this;
    }

    /**
     * Drop
     */
    public function drop(\Closure $columns)
    {
        //Set columns
        $columns(new TableColumnDrop($this, 'drop'));

        //Add comer if needed
        if(isset($this->columns['add']) || isset($this->columns['modify'])){
            sql::raw(self::ALTER_TABLE)->add(', ');
        }

        //Modify columns
        if(isset($this->columns['drop'])) {

            //Generate columns
            $parameters = $this->generateColumns(self::ALTER_TABLE, $this->columns['drop'],
                [], [], function($key,$column){
                return self::DROP_COLUMN . " " . $this->accent($column->column_name);
            });

            //Add parameters
            sql::raw(self::ALTER_TABLE)->add(null, $parameters);
        }

        $append = (!empty($this->columns['add']) || !empty($this->columns['modify'])) ? true : false;

        $this->generateConstraint(self::ALTER_TABLE, $this->primaryKeys, $append, function($name){
            $drop = self::DROP . " " .  TableColumnDefinitionInterface::PRIMARY_KEY;

            if($name){
                $drop .= " " . $this->accent($name);
            }

            return $drop;
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->uniqueKeys, $append, function($name){
            return self::DROP . " " .  TableColumnDefinitionInterface::INDEX . " " . $this->accent($name);
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->indexs, $append, function($name){
            return self::DROP . " " .  TableColumnDefinitionInterface::INDEX . " " . $this->accent($name);
        });

        return $this;
    }

    /**
     * Rename Table
     * @param $name
     */
    public function renameTable($name)
    {
        Sql::raw(self::RENAME_TABLE)->add(self::RENAME_TABLE . ' ' . $this->accent($this->table->name()) . ' TO ' . $this->accent($name) . ';');
    }

    /**
     * Drop Table
     */
    public function dropTable()
    {
        Sql::raw(self::DROP_TABLE)->add(self::DROP_TABLE . ' ' . $this->accent($this->table->name()) . ';');
    }

    /**
     * Handle Modify
     * @param $column
     */
    public function handleModify($column)
    {
        $this->modify[] = $column;
    }

    /**
     * Handle Column
     * @param TableColumnDataTypeAttributeInterface $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDataTypeAttributeInterface $column, $type = 'default')
    {
        $this->columns[$type][] = $column;
    }

    /**
     * Handle Primary Key
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handlePrimaryKey($name,array $columns)
    {
        $this->primaryKeys[$name] = $columns;
    }

    /**
     * Handle Unique Key
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handleUniqueKey($name,array $columns)
    {
        $this->uniqueKeys[$name] = $columns;
    }

    /**
     * Handle Index
     * @param $name
     * @param array $columns
     * @return void
     */
    public function handleIndex($name,array $columns)
    {
        $this->indexs[$name] = $columns;
    }
}