<?php namespace freidcreations\freidQuery\sql\statement;
use freidcreations\freidQuery\config\database AS config;
use freidcreations\freidQuery\connection\database;
use freidcreations\freidQuery\sql\table;
use freidcreations\freidQuery\sql\sql;

/**
 * @name statement
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class statement extends database
{
    const AND_WHERE = 'AND';
    const COLS = 'COL';
    const COL_AS = 'AS';
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

    public static $instances;
    public static $queries_count = 0;

    protected $multipleOn;
    protected $multipleOrders;
    protected $multipleWheres;
    protected $nestedWheres;
    protected $pagination;
    protected $table;

    private $debug = false;
    private $orm;
    private $type;

    /**
     * Merge Parameters
     *
     * @param string $type
     * @param sql $parameters
     * @return $this
     */
    public function mergeParameters($type, sql $parameters)
    {
        foreach($parameters->parameters() as $parameter){
            sql::raw($type)->add(null,[$parameter]);
        }
        return $this;
    }

    /**
     * On Statement
     *
     * @param $a
     * @param $operator
     * @param $b
     * @return string
     */
    public function on($a, $operator, $b, $clause = self::ON)
    {
        $clause = ($this->multipleOn && $clause == self::ON) ? self::AND_WHERE : $clause;
        $questionMarks = strpos($operator,'?');
        $foreignKey = empty($questionMarks) ? $this->addAccent($b) : '';

        $this->multipleOn = true;
        return [
            'sql'        => $clause . ' ' . $this->addAccent($a) . ' ' . $operator . ' ' . $foreignKey,
            'parameters' => empty($questionMarks) ? [] : [$b]
        ];
    }

    /**
     * Recall
     *
     * @param statement $statement
     * @return statement
     */
    public function recall(statement $statement)
    {
        return $statement;
    }

    /**
     * Reset SQL
     */
    public function reset()
    {
        sql::reset();
        $this->multipleWheres = false;
        $this->multipleOrders = false;
        $this->multipleOn = false;
        $this->orm = false;
        return $this;
    }

    /**
     * Add Accent
     *
     * @param string $string
     * @param bool $tableName
     * @return string
     */
    protected function addAccent($string, $tableName = false)
    {
        $items = explode('.',$string);
        $return = '';
        foreach($items as $key => $item){
            $dot = ($key != (count($items)-1)) ? '.' : '';
            if(self::activeDBDrive() == config::DRIVE_POSTGRESQL) {
                if($tableName) {
                    $return .= '"' . $item . '"';
                }else {
                    $return .= '"' . $item . '"' . $dot;
                }
            }else if(self::activeDBDrive() == config::DRIVE_MYSQL) {
                $return .= '`' . $item . '`' . $dot;
            }
        }
        return $return;
    }

    /**
     * Statement Constructor
     *
     * @param table $table
     * @param null $type
     */
    protected function __construct(table $table, $type = null)
    {
        //Whats our active db connection?
        if(!self::$stick) {
            self::$active = config::detectEnvironment();
        }

        //How many queries have run?
        self::$queries_count++;

        //Set
        $this->table = $table;
        $this->type = $type;
        $this->orm = false;
    }

    /**
     * Execute
     *
     * @return \PDOStatement
     * @throws \Exception
     */
    protected function execute()
    {
        //Build SQL
        $sql = $this->build();

        //Execute query
        $query = self::dbh()->prepare($sql->sql());
        $query->execute($sql->parameters());

        //Any errors?
        if($query->errorCode() != 0) {
            if(config::detectEnvironment() == 'local'){
                throw new \Exception( $query->errorInfo()[2] );
            }
        }

        //If debugging then dump params
        if($this->debug){
            if(config::detectEnvironment() == 'local') {
                $query->debugDumpParams();
            }
        }

        return $query;
    }

    /**
     * Join
     *
     * @param array $join
     * @param \Closure $on
     * @return $this
     * @throws \Exception
     */
    protected function join(array $join, $on, $type = self::INNER_JOIN){
        $this->multipleOn = false;
        foreach ($join as $key => $table) {
            if ($table instanceof table) {
                sql::raw(self::JOIN)->add($type . ' ' . self::addAccent($table->name(), true) . ' ' . self::COL_AS . ' ' . $key);

                //On statement
                if ($on instanceof \Closure) {
                    foreach ($on() as $condition) {
                        sql::raw(self::JOIN)->add($condition['sql'], $condition['parameters']);
                    }
                }else if(is_array($on) && count($on) == 3) {
                    $single = $this->on($on[0], $on[1], $on[2]);
                    sql::raw(self::JOIN)->add($single['sql'], $single['parameters']);
                }else if( !empty($on) ) {
                    throw new \Exception( 'On statement can only be a instance of closure or an array.' );
                }
            }else if($join instanceof sql) {
                sql::raw(self::JOIN)->add($type . '( ' . $join->sql() . ' )' . self::COL_AS . ' ' . $key);

                //Merge parameters
                $this->mergeParameters(self::JOIN, $join);
            }else {
                throw new \Exception('Join table must be of type table or sql');
            }
        }
        return true;

    }

    /**
     * Where Statement
     *
     * @param string $column
     * @param string $operator ( = | > | < | <> | >= | =< | BETWEEN | LIKE | IN )
     * @param string $value
     * @param string $clause
     * @return $this
     */

    protected function where($column, $operator = null, $value = null, $clause = self::WHERE)
    {
        //Determine what clause to use (WHERE AND OR)
        if (empty($this->multipleWheres) && !($column instanceof \Closure)) {
            $this->multipleWheres = true;
        }elseif ($clause == self::WHERE) {
            $clause = self::AND_WHERE;
        }

        //Do we have a nested where?
        if($column instanceof \Closure) {
            $this->nestedWheres++;

            $closure = $column();
            $nestedWhere = [];
            foreach ( $closure as $key => $row ) {
                $column = $row['column'];
                if( $key == 0 ) {
                    $column = '( ' . $row['column'];
                }

                $operator = $row['operator'];
                if( $key == count( $closure ) - 1 ){
                    $operator = $row['operator'] . ' )';
                }

                $nestedWhere[] = [
                    'clause'     => $row['clause'],
                    'column'     => $column,
                    'operator'   => $operator,
                    'parameters' => $row['parameters']
                ];
            }

            $this->nestedWheres--;
            if($this->nestedWheres > 0) {
                return $nestedWhere;
            }else {
                foreach( $nestedWhere as $key => $row ){
                    sql::raw(self::WHERE)->add($row['clause'] . ' ' . $row['column'] . ' ' . $row['operator'], $row['parameters']);
                }
            }
        }else {
            //Return our nested statement or add our statement to sql
            if($this->nestedWheres > 0){
                return [
                    'clause'     => $clause,
                    'column'     => $column,
                    'operator'   => $operator,
                    'parameters' => [$value]
                ];
            }else {
                sql::raw(self::WHERE)->add($clause . ' ' . $column . ' ' . $operator, [$value]);
            }
        }

        return $this;
    }

    /**
     * Where In Statement
     *
     * @param $column
     * @param array $in
     * @param string $clause
     * @return $this
     */
    protected function whereIn($column, array $in, $clause = self::WHERE)
    {
        $questionMarks = str_repeat('?,', count( $in ) - 1 ) . '?';

        $parameters = [];

        foreach ($in as $value) {
            $parameters[] = $value;
        }

        if (empty($this->multipleWheres)) {
            $this->multipleWheres = true;
        } elseif ($clause == self::WHERE) {
            $clause = self::AND_WHERE;
        }

        sql::raw(self::WHERE)->add($clause . ' ' . $column . ' ' . self::IN . ' (' . $questionMarks . ')', $parameters);

        return $this;
    }

    /**
     * Build
     *
     * @return sql
     */
    private function build()
    {
        //Build order for query
        $buildOrder = [
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
        $sql = new sql();
        foreach( $buildOrder as $index => $type){
            $parameters = [];
            foreach( sql::raw($type)->parameters() as $key => $parameter){
                $parameters[] = $parameter;
            }

            //Build query
            $sql->add(sql::raw($type)->sql(),$parameters);
        }

        return $sql;
    }
}
?>
