<?php namespace freidcreations\QueryMule\Builder\Sql\Mysql;
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
     * @var bool
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
        $this->makeAccent($this);
        $this->makeTableColumn($this);
        $this->makeBuilder($this);
    }

    /**
     * Make
     *
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
        if(isset($this->columns['add'])){
            sql::raw(self::ALTER_TABLE)->add(', ');
        }

        //Change columns
        if(isset($this->columns['change'])) {
            $parameters = $this->generateColumns(self::ALTER_TABLE, $this->columns['change'], [
                'null',
                'not_null',
                'default',
                'comment',
                'first',
                'after',
                'rename',
                'drop'
            ], [], function($key,$column){
                return self::CHANGE . " " . $this->accent($this->modify[$key]) . " " . $this->accent($column->column_name);
            });

            //Add parameters
            sql::raw(self::ALTER_TABLE)->add(null, $parameters);
        }

        //Modify columns
        if(isset($this->columns['modify'])) {

            //Add comer if needed
            if(isset($this->columns['change'])){
                sql::raw(self::ALTER_TABLE)->add(', ');
            }

            //Generate columns
            $parameters = $this->generateColumns(self::ALTER_TABLE, $this->columns['modify'], [
                'null',
                'not_null',
                'default',
                'comment',
                'first',
                'after',
                'rename',
                'drop'
            ], [], function($key,$column){
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
            return self::ADD . " " . TableColumnDefinitionInterface::UNIQUE_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint(self::ALTER_TABLE, $this->indexs, $append, function($name,$column){
            return self::ADD . " " . TableColumnDefinitionInterface::INDEX . " " . $this->accent($name) . " (" . $column . ")";
        });

        //Modify columns
//        $parameters = [];
//        if(isset($this->columns['modify'])) {
//            $keys = array_keys($this->columns['modify']);
//
//            $index = 0;
//            foreach ($this->columns['modify'] as $name => $column) {
//                if ($column instanceof TableColumnDefinitionInterface) {
//
//                    //Modify column name
//                    if (isset($this->modify[$index]) &&
//                        $this->modify[$index] != $column->getAttribute('name')
//                    ) {
//                        sql::raw(self::MODIFY)->add(self::CHANGE . ' ' . $this->accent($this->modify[$index]) . ' ' . $this->accent($column->getAttribute('name')));
//                    } else {
//                        sql::raw(self::MODIFY)->add(self::MODIFY_COLUMN . ' ' . $this->accent($column->getAttribute('name')));
//                    }
//
//                    //Modify column definition
//                    foreach ([
//                                 'data_type',
//                                 'null',
//                                 'not_null',
//                                 'default',
//                                 'auto_increment',
//                                 'comment',
//                                 'first',
//                                 'after',
//                                 'rename',
//                                 'drop'
//                             ] as $attribute) {
//                        if ($column->getAttribute($attribute)) {
//
//                            //Has Attribute?
//                            if ($column->hasAttribute($attribute)) {
//                                sql::raw(self::MODIFY)->add($column->getAttribute($attribute));
//
//                                //Has Parameters?
//                                if ($column->hasParameter($attribute)) {
//                                    $parameters[] = $column->getParameter($attribute);
//                                }
//                            }
//                        }
//                    }
//
//                    //Is primary key?
//                    if ($column->getAttribute('primary_key')) {
//                        $primary[] = $this->accent($column->getAttribute('name'));
//                    }
//
//                    //Is unique key?
//                    if ($column->getAttribute('unique_key')) {
//                        $unique[] = $this->accent($column->getAttribute('name'));
//                    }
//
//                    //Do we need to add a comer?
//                    if ($name != $keys[count($keys) - 1]) {
//                        sql::raw(self::MODIFY)->add(', ');
//                    }
//                }
//
//                $index++;
//            }
//        }
//
//        //Add primary keys
//        foreach($this->primaryKeys as $primary){
//            if($this->columns) {
//                sql::raw(self::MODIFY)->add(', ');
//            }
//
//            sql::raw(self::MODIFY)->add(self::ADD . ' ' . TableColumnDefinitionInterface::PRIMARY_KEY . "(" . implode(", ", $primary) . ")");
//        }
//
//        //Add unique
//        foreach($this->uniqueKeys as $name => $unique){
//            if($this->columns) {
//                sql::raw(self::MODIFY)->add(', ');
//            }
//
//            sql::raw(self::MODIFY)->add(self::ADD . ' ' . self::CONSTRAINT . ' ' . $name . ' ' . TableColumnDefinitionInterface::UNIQUE_KEY . "(" . implode(", ", $unique) . ")");
//        }
//
//        //Add index
//        foreach($this->indexs as $name => $index){
//            if($this->columns) {
//                sql::raw(self::MODIFY)->add(', ');
//            }
//
//            sql::raw(self::MODIFY)->add(self::ADD . ' ' . TableColumnDefinitionInterface::INDEX . ' ' . $name . ' (' . implode(", ", $index) . ')');
//        }
//
//        return $this;
    }

    /**
     * Rename
     * @param $newName
     */
    public function rename($newName)
    {

    }

    /**
     * Drop
     */
    public function drop()
    {

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
    public function handleColumn(TableColumnDataTypeAttributeInterface $column, $type = null)
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

