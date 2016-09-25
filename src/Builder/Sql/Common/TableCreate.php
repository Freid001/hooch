<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\AbstractStatement;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;

/**
 * Class TableCreate
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableCreate extends AbstractStatement implements TableColumnHandlerInterface
{
    const CREATE_TEMPORARY_TABLE  = 'CREATE TEMPORARY TABLE';
    const IF_NOT_EXISTS = 'IF NOT EXISTS';

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
     * Create
     * @param \Closure $columns
     * @param $temporary
     * @param $ifNotExists
     * @return $this
     */
    public function create(\Closure $columns, $temporary = false, $ifNotExists = true)
    {
        //Set columns
        $columns(new TableColumnAdd($this));

        //Is temporary?
        if($temporary) {
            Sql::raw(self::CREATE_TABLE)->add(self::CREATE_TEMPORARY_TABLE);
        }else {
            Sql::raw(self::CREATE_TABLE)->add(self::CREATE_TABLE);
        }

        //If not exists?
        if($ifNotExists){
            Sql::raw(self::CREATE_TABLE)->add(self::IF_NOT_EXISTS . ' ' . $this->addAccent($this->table->name()) . ' (');
        }else {
            Sql::raw(self::CREATE_TABLE)->add($this->addAccent($this->table->name()) . ' (');
        }

        //Add columns
        $parameters = [];
        if($this->columns) {
            $keys = array_keys($this->columns);

            foreach ($this->columns as $name => $column) {
                if($column instanceof TableColumnDefinition){

                    //Add column name
                    sql::raw(self::CREATE_TABLE)->add($this->addAccent($column->getAttribute('name')));

                    //Add column definition
                    foreach([
                        'data_type',
                        'null',
                        'not_null',
                        'default',
                        'auto_increment',
                        'comment',
                            ] as $attribute){
                        if($column->getAttribute($attribute)) {

                            //Has Attribute?
                            if($column->hasAttribute($attribute)) {
                                sql::raw(self::CREATE_TABLE)->add($column->getAttribute($attribute));

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
                        sql::raw(self::CREATE_TABLE)->add(', ');
                    }
                }
            }

            //Add primary keys
            foreach( $this->primaryKeys as $primary){
                sql::raw(self::CREATE_TABLE)->add(', ');
                sql::raw(self::CREATE_TABLE)->add(TableColumnDefinition::PRIMARY_KEY . "(" . implode(", ", $primary) . ")");
            }

            //Add unique keys
            foreach( $this->uniqueKeys as $unique){
                sql::raw(self::CREATE_TABLE)->add(', ');
                sql::raw(self::CREATE_TABLE)->add(TableColumnDefinition::UNIQUE_KEY . "(" . implode(", ", $unique) . ")");
            }

            //Add index
            foreach( $this->indexs as $index){
                sql::raw(self::CREATE_TABLE)->add(', ');
                sql::raw(self::CREATE_TABLE)->add(TableColumnDefinition::INDEX . "(" . implode(", ", $index) . ")");
            }
        }

        Sql::raw(self::CREATE_TABLE)->add(')',$parameters);

        return $this;
    }

    /**
     * Handle Modify
     * @param $column
     */
    public function handleModify($column){
        //nothing
    }

    /**
     * Handle Column
     * @param TableColumnDefinitionInterface $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDefinitionInterface $column, $type = null)
    {
        $this->columns[] = $column;
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
     * @param array $columns
     * @return void
     */
    public function handleUniqueKey(array $columns)
    {
        $this->uniqueKeys[] = $columns;
    }

    /**
     * Handle Index
     * @param array $columns
     * @return void
     */
    public function handleIndex(array $columns)
    {
        $this->indexs[] = $columns;
    }
}

