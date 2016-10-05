<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Connection\Database;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Sql\Sql;

/**
 * Class AbstractStatement
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
abstract class AbstractStatement
{
    const ADD = 'ADD';
    const ALTER_TABLE = 'ALTER TABLE';
    const AND_WHERE = 'AND';
    const CREATE_TABLE = 'CREATE TABLE';
    const COLS = 'COL';
    const COL_AS = 'AS';
    const CONSTRAINT = 'CONSTRAINT';
    const COUNT = 'COUNT';
    const DELETE = 'DELETE';
    const DISTINCT = 'DISTINCT';
    const FETCH = 'fetch';
    const FETCH_ALL = 'fetchAll';
    const FETCH_COLUMN = 'fetchColumn';
    const FETCH_COLUMN_COUNT = 'columnCount';
    const FETCH_COLUMN_META = 'getColumnMeta';
    const FETCH_ROW_COUNT = 'rowCount';
    const FROM = 'FROM';
    const GROUP = 'GROUP BY';
    const HAVING = 'HAVING';
    const IN = 'IN';
    const INNER_JOIN = 'INNER JOIN';
    const INSERT = 'INSERT';
    const INTO = 'INTO';
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    const LIMIT = 'LIMIT';
    const MODIFY = 'MODIFY';
    const ON = 'ON';
    const OR_WHERE = 'OR';
    const ORDER = 'ORDER BY';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const RIGHT_OUTER_JOIN = 'RIGHT OUTER JOIN';
    const SET = 'SET';
    const SELECT = 'SELECT';
    const SQL_STAR = '*';
    const TABLE = 'TABLE';
    const UPDATE = 'UPDATE';
    const VALUES = 'VALUES';
    const WHERE = 'WHERE';

    /**
     * @var Table
     */
    protected $table;

    /**
     * Statement constructor.
     * @param Table $table
     */
    protected function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Recall
     *
     * @param AbstractStatement $statement
     * @return AbstractStatement
     */
    public function recall(AbstractStatement $statement)
    {
        return $statement;
    }

    /**
     * Reset SQL
     * @return $this
     */
    public function reset()
    {
        Sql::reset();
        return $this;
    }

    /**
     * Execute
     * @return \PDOStatement
     * @throws \Exception
     */
    public function execute()
    {
        //Build SQL
        $sql = $this->build();

        //Execute query
        $query = $this->table->dbh()->connection()->prepare($sql->sql());
        $query->execute($sql->parameters());

        //Any errors?
        //if($query->errorCode() != 0) {
//            if(config::detectEnvironment() == 'local'){
                throw new \Exception( $query->errorInfo()[2] );
//            }
        //}

        //If debugging then dump params
        //if($this->debug){
//            if(config::detectEnvironment() == 'local') {
//                $query->debugDumpParams();
//            }
        //}

        return $query;
    }

    /**
     * Build
     *
     * @return sql
     */
    public function build()
    {
        //Build order for query
        $buildOrder = [
            self::CREATE_TABLE,
            self::ALTER_TABLE,
            self::ADD,
            self::MODIFY,
            self::SELECT,
            self::INSERT,
            self::UPDATE,
            self::DELETE,
            self::COLS,
            self::INTO,
            self::VALUES,
            self::FROM,
            self::JOIN,
            self::WHERE,
            self::GROUP,
            self::ORDER,
            self::HAVING,
            self::LIMIT
        ];

        //Build Query
        $sql = new Sql();
        foreach( $buildOrder as $index => $type){
            $parameters = [];
            foreach(Sql::raw($type)->parameters() as $key => $parameter){
                $parameters[] = $parameter;
            }

            //Build query
            $sql->add(Sql::raw($type)->sql(),$parameters);
        }

        return $sql;
    }

}