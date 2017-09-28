<?php namespace freidcreations\QueryMule\Builder\Sql\Pgsql;
use freidcreations\QueryMule\Query\Sql\Common\HasTableColumn;
use freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter\TableColumnDataTypeIncrementAdapter;
use freidcreations\QueryMule\Builder\Sql\Pgsql\Adapter\TableColumnDataTypeIntAdapter;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;
use freidcreations\QueryMule\Query\Sql\Common\QueryBuilderInterface;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Query\Sql\Common\HasAccent;
use freidcreations\QueryMule\Query\Sql\Common\HasBuilder;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAttributeInterface;

/**
 * Class TableCreate
 * @package freidcreations\QueryMule\Builder\Sql\Pgsql
 */
class TableCreate implements QueryBuilderInterface, TableColumnHandlerInterface
{
    use HasAccent;
    use HasTableColumn;
    use HasBuilder;

    const CREATE_INDEX = 'CREATE INDEX';
    const UNIQUE = 'UNIQUE';

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
            Sql::raw(self::CREATE_TABLE)->add(self::IF_NOT_EXISTS . ' ' . $this->accent($this->table->name()) . ' (');
        }else {
            Sql::raw(self::CREATE_TABLE)->add($this->accent($this->table->name()) . ' (');
        }

        //Generate columns for this table
        $parameters = $this->generateColumns(self::CREATE_TABLE,$this->columns,[
            'null',
            'not_null',
            'default'
        ],[
            'increment' => new TableColumnDataTypeIncrementAdapter(),
            'int' => new TableColumnDataTypeIntAdapter()
        ],function($key, $column){
            return $this->accent($column->column_name);
        });

        $this->generateConstraint(self::CREATE_TABLE,$this->primaryKeys,true,function($name,$column){
            return self::CONSTRAINT . " " . $this->accent($name) . " " . TableColumnDefinition::PRIMARY_KEY . " (" . $column . ")";
        });

        $this->generateConstraint(self::CREATE_TABLE,$this->uniqueKeys,true,function($name,$column){
            return self::CONSTRAINT . " " . $this->accent($name) . " " . self::UNIQUE . " (" . $column . ")";
        });

        Sql::raw(self::CREATE_TABLE)->add(');',$parameters);

        $this->generateConstraint(self::CREATE_TABLE,$this->indexs,false,function($name,$column){
            return self::CREATE_INDEX  . " " . $this->accent($name) . " " . self::ON . " " . $this->accent($this->table->name()) . " (" . $column . ");";
        });

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
     * @param TableColumnDataTypeAttributeInterface $column
     * @param null|string $type
     */
    public function handleColumn(TableColumnDataTypeAttributeInterface $column, $type = 'default')
    {
        $this->columns[] = $column;
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
    public function handleIndex($name,array $columns){
        $this->indexs[$name] = $columns;
    }
}

