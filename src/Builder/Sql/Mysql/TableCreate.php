<?php namespace freidcreations\QueryMule\Builder\Sql\Mysql;
use freidcreations\QueryMule\Query\Sql\Common\HasBuilder;
use freidcreations\QueryMule\Query\Sql\Common\HasTableColumn;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDefinition;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnHandlerInterface;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataTypeAttribute;
use freidcreations\QueryMule\Query\Sql\Common\HasAccent;
use freidcreations\QueryMule\Query\Sql\Common\QueryBuilderInterface;

/**
 * Class TableCreate
 * @package freidcreations\QueryMule\Builder\Sql\Mysql
 */
class TableCreate implements QueryBuilderInterface, TableColumnHandlerInterface
{
    use HasAccent;
    use HasTableColumn;
    use HasBuilder;

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
            'default',
            'comment'
        ]);

        $this->generateConstraint($this->primaryKeys,true,function($name,$column){
            return TableColumnDefinition::PRIMARY_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint($this->uniqueKeys,true,function($name,$column){
            return TableColumnDefinition::UNIQUE_KEY . " " . $this->accent($name) . " (" . $column . ")";
        });

        $this->generateConstraint($this->indexs,true,function($name,$column){
            return TableColumnDefinition::INDEX . " " . $this->accent($name) . " (" . $column . ")";
        });

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