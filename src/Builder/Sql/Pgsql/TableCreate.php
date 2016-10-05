<?php namespace freidcreations\QueryMule\Builder\Sql\Pgsql;
use freidcreations\QueryMule\Builder\Sql\Common\HasTableColumn;
use freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter\TableColumnDataTypeIncrementAdapter;
use freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter\TableColumnDataTypeIntAdapter;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;
use freidcreations\QueryMule\Query\Sql\Common\AbstractStatement;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataTypeAttribute;

/**
 * Class TableCreate
 * @package freidcreations\QueryMule\Builder\Sql\Pgsql
 */
class TableCreate extends AbstractStatement implements TableColumnHandlerInterface
{
    use HasTableColumn;


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
            Sql::raw(self::CREATE_TABLE)->add(self::IF_NOT_EXISTS . ' ' . $this->addAccent($this->table,$this->table->name()) . ' (');
        }else {
            Sql::raw(self::CREATE_TABLE)->add($this->addAccent($this->table,$this->table->name()) . ' (');
        }

        //Generate columns for this table
        $parameters = $this->generateColumns(self::CREATE_TABLE,$this->table,$this->columns,[
            'null',
            'not_null',
            'default'
        ],[
            'increment' => new TableColumnDataTypeIncrementAdapter(),
            'int' => new TableColumnDataTypeIntAdapter()
        ]);

        //Add primary keys
        foreach($this->primaryKeys as $primary){
            if($this->columns) {
                sql::raw(self::CREATE_TABLE)->add(', ');
            }

            sql::raw(self::CREATE_TABLE)->add(TableColumnDefinition::PRIMARY_KEY . "(" . implode(", ", $primary) . ")");
        }

        //Add unique keys
        foreach($this->uniqueKeys as $name => $unique){
            if($this->columns) {
                sql::raw(self::CREATE_TABLE)->add(', ');
            }

            sql::raw(self::CREATE_TABLE)->add(TableColumnDefinition::UNIQUE_KEY . " " . $name . " (" . implode(", ", $unique) . ")");
        }

        Sql::raw(self::CREATE_TABLE)->add(')',$parameters);

        return $this;
    }

    /**
     * Handle Modify
     * @param $column
     * @throws \Exception
     */
    public function handleModify($column){}

    /**
     * Handle Column
     * @param TableColumnDataTypeAttribute $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDataTypeAttribute $column, $type = null)
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
    public function handleIndex($name,array $columns){}
}

