<?php namespace freidcreations\QueryMule\Builder\Sql\Pgsql;
use freidcreations\QueryMule\Query\Sql\Common\AbstractStatement;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;

/**
 * Class TableAlter
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableAlter extends AbstractStatement implements TableColumnHandlerInterface
{
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
     * Alter
     * @return $this
     */
    public function alter()
    {
        Sql::raw(self::ALTER_TABLE)->add(self::ALTER_TABLE . ' ' . $this->addAccent($this->table->name()));
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
        $parameters = [];
        if(isset($this->columns['add'])) {
            $keys = array_keys($this->columns['add']);

            foreach ($this->columns['add'] as $name => $column) {
                if($column instanceof TableColumnDefinition){

                    //Add column name
                    sql::raw(self::ADD)->add(self::ADD_COLUMN . ' ' . $this->addAccent($column->getAttribute('name')));

                    //Add column definition
                    foreach([
                                'data_type',
                                'null',
                                'not_null',
                                'default',
                                'auto_increment',
                                'comment',
                                'first',
                                'after'
                            ] as $attribute){
                        if($column->getAttribute($attribute)) {

                            //Has Attribute?
                            if($column->hasAttribute($attribute)) {
                                sql::raw(self::ADD)->add($column->getAttribute($attribute));

                                //Has Parameters?
                                if ($column->hasParameter($attribute)) {
                                    $parameters[] = $column->getParameter( $attribute );
                                }
                            }
                        }
                    }

                    //Is primary key?
                    if($column->getAttribute('primary_key')){
                        $primary[] = $this->addAccent($column->getAttribute('name'));
                    }

                    //Is unique key?
                    if($column->getAttribute('unique_key')){
                        $unique[] = $this->addAccent($column->getAttribute('name'));
                    }

                    //Do we need to add a comer?
                    if ($name != $keys[count($keys)-1]) {
                        sql::raw(self::ADD)->add(', ');
                    }
                }
            }

            //Add primary keys
            foreach( $this->primaryKeys as $primary){
                sql::raw(self::ADD)->add(', ');
                sql::raw(self::ADD)->add(self::ADD . ' ' . TableColumnDefinition::PRIMARY_KEY . "(" . implode(", ", $primary) . ")");
            }

            //Add unique keys
            foreach( $this->uniqueKeys as $unique){
                sql::raw(self::ADD)->add(', ');
                sql::raw(self::ADD)->add(self::ADD . ' ' . TableColumnDefinition::UNIQUE_KEY . "(" . implode(", ", $unique) . ")");
            }

            //Add index
            foreach( $this->indexs as $index){
                sql::raw(self::ADD)->add(', ');
                sql::raw(self::ADD)->add(self::ADD . ' ' . TableColumnDefinition::INDEX . "(" . implode(", ", $index) . ")");
            }
        }

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
            sql::raw(self::MODIFY)->add(', ');
        }

        //Modify columns
        $parameters = [];
        if(isset($this->columns['modify'])) {
            $keys = array_keys($this->columns['modify']);

            $index = 0;
            foreach ($this->columns['modify'] as $name => $column) {
                if ($column instanceof TableColumnDefinition) {

                    //Modify column name
                    if (isset($this->modify[$index]) &&
                        $this->modify[$index] != $column->getAttribute('name')
                    ) {
                        sql::raw(self::MODIFY)->add(self::CHANGE . ' ' . $this->addAccent($this->modify[$index]) . ' ' . $this->addAccent($column->getAttribute('name')));
                    } else {
                        sql::raw(self::MODIFY)->add(self::MODIFY_COLUMN . ' ' . $this->addAccent($column->getAttribute('name')));
                    }

                    //Modify column definition
                    foreach ([
                                 'data_type',
                                 'null',
                                 'not_null',
                                 'default',
                                 'auto_increment',
                                 'comment',
                                 'first',
                                 'after',
                                 'rename',
                                 'drop'
                             ] as $attribute) {
                        if ($column->getAttribute($attribute)) {

                            //Has Attribute?
                            if ($column->hasAttribute($attribute)) {
                                sql::raw(self::MODIFY)->add($column->getAttribute($attribute));

                                //Has Parameters?
                                if ($column->hasParameter($attribute)) {
                                    $parameters[] = $column->getParameter($attribute);
                                }
                            }
                        }
                    }

                    //Is primary key?
                    if ($column->getAttribute('primary_key')) {
                        $primary[] = $this->addAccent($column->getAttribute('name'));
                    }

                    //Is unique key?
                    if ($column->getAttribute('unique_key')) {
                        $unique[] = $this->addAccent($column->getAttribute('name'));
                    }

                    //Do we need to add a comer?
                    if ($name != $keys[count($keys) - 1]) {
                        sql::raw(self::MODIFY)->add(', ');
                    }
                }

                $index++;
            }
        }

        //Add primary keys
        foreach($this->primaryKeys as $primary){
            if($this->columns) {
                sql::raw(self::MODIFY)->add(', ');
            }

            sql::raw(self::MODIFY)->add(self::ADD . ' ' . TableColumnDefinition::PRIMARY_KEY . "(" . implode(", ", $primary) . ")");
        }

        //Add unique
        foreach($this->uniqueKeys as $name => $unique){
            if($this->columns) {
                sql::raw(self::MODIFY)->add(', ');
            }

            sql::raw(self::MODIFY)->add(self::ADD . ' ' . self::CONSTRAINT . ' ' . $name . ' ' . TableColumnDefinition::UNIQUE_KEY . "(" . implode(", ", $unique) . ")");
        }

        //Add index
        foreach($this->indexs as $name => $index){
            if($this->columns) {
                sql::raw(self::MODIFY)->add(', ');
            }

            sql::raw(self::MODIFY)->add(self::ADD . ' ' . TableColumnDefinition::INDEX . ' ' . $name . ' (' . implode(", ", $index) . ')');
        }

        return $this;
    }

    /**
     * Rename
     * @param $newName
     */
    public function rename($newName)
    {}

    /**
     * Drop
     */
    public function drop()
    {}

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
     * @param TableColumnDefinitionInterface $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDefinitionInterface $column, $type = null)
    {
        $this->columns[$type][] = $column;
    }

    /**
     * Handle Primary Key
     * @param array $columns
     * @return void
     */
    public function handlePrimaryKey(array $columns)
    {
        $this->primaryKeys[] = $columns;
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
     * @param array $columns
     * @return void
     */
    public function handleIndex($name,array $columns)
    {
        $this->indexs[$name] = $columns;
    }
}

