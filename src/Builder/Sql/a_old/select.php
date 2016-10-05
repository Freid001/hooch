<?php namespace freidcreations\freidQuery\sql\statement;
use freidcreations\freidQuery\sql\table;
use freidcreations\freidQuery\sql\sql;

/**
 * @name select
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class select extends statement
{
    /**
     * Cols
     *
     * @param array $cols
     * @param null $alias
     * @param table|false $table
     * @return $this
     */
    public function cols($cols = [self::SQL_STAR], $alias = null, table $table = null)
    {
        //Default to this table
        if(empty($table)){
            $table = $this->table;
        }

        //Add Accent
        $position = 0;
        foreach($cols as $key => &$col){
            //Increment position only when we have not passed a key and we are using the default int position
            if((int)$key !== $position){
                $position++;
            }

            //Are we using a SQL_STAR?
            if($col != self::SQL_STAR){
                $as = ($key !== $position) ? self::addAccent($key) . ' ' . self::COL_AS . ' ' : null;
                if(!empty($alias)) {
                    $col = self::addAccent($alias) . '.' . $as . self::addAccent($col);
                }else{
                    $col = $as . self::addAccent($col);
                }
            }else {
                if(!empty($alias)){
                    $col = $alias . '.' . $col;
                }
            }
        }

        //Do we need a comma?
        $comma = empty(sql::raw(self::COLS)->sql()) ? '' :', ';

        //Add sql
        if(!empty($cols)) {
            sql::raw(self::COLS)->add( $comma . implode(', ', $cols));
        }

        //From this table?
        if($table->name() == $this->table->name){
            $this->from($this->table,$alias);
        }

        return $this;
    }

    /**
     * Sum
     * @param array $cols
     * @param null $equals
     */
    public function sum( $col, $equals = null ){

    }

    public function avg( $col ){

    }

    public function min( $col ){

    }

    public function max( $col ){

    }

    public function group_concat(){

    }

    /**
     * Columns
     * @param array $cols
     * @param null $alias
     * @param table|false $table
     * @return $this
     */
    public function cols2( $cols = [ self::SQL_STAR ], $alias = null, table $table = null ){
        //Default to this table
        if( empty( $table ) ) {
            $table = $this->table;
        }

        //If * then get all table columns
//        if( $cols == [ self::SQL_STAR ] ) {
//            $cols = [];
//            foreach( $table->columns() as $key => $value ){
//                $cols[] = $key;
//            }
//        }

        //Add Accent
        $position = 0;
        foreach($cols as $key => &$col ){
            //Increment position only when we have not passed a key and we are using the default int position
            if( (int)$key !== $position ){
                $position++;
            }

            //Are we using a SQL_STAR?
            if( $col != self::SQL_STAR ) {
                $as = ( $key !== $position ) ? self::addAccent( $key ) . ' ' . self::COL_AS . ' ' : null;
                if( !empty( $alias ) ) {
                    $col = self::addAccent( $alias ) . '.' . $as . self::addAccent( $col );
                }else{
                    $col = $as . self::addAccent( $col );
                }
            }else {
                if( !empty( $alias ) ) {
                    $col = $alias . '.' . $col;
                }
            }
        }

        //Do we need a comma?
        $comma = empty(sql::raw(self::COLS)->sql()) ? '' :', ';

        //Add sql
        if(!empty($cols)) {
            sql::raw(self::COLS)->add($comma . implode(', ', $cols));
        }

        return $this;
    }

    /**
     * From
     *
     * @param $from
     * @param string $alias
     * @param array $cols
     * @return $this
     * @throws \Exception
     */
    public function from($from, $alias = null, $cols = [self::SQL_STAR])
    {
        //Table or raw sql?
        if($from instanceof table){
            $table = $from->name();

            //Alias
            $alias = !empty($alias) ? self::COL_AS . ' ' . $alias : '';

            //Add sql
            sql::reset(self::FROM);
            sql::raw(self::FROM)->add(self::FROM . ' ' . $this->addAccent($table,true) . ' ' . $alias);
        }else if($from instanceof sql){
            //Table SQL
            $table = $from->sql();

            //Merge parameters
            $this->mergeParameters(self::FROM, $from);

            //Create new table
            $newTable = new table(null);
            foreach($cols as $col){
                $newTable->$col = null;
            }

            //Add cols
            $this->cols($cols, $alias, $newTable);

            //Alias
            $alias = !empty($alias) ? self::COL_AS . ' ' . $alias : '';

            //Add sql
            sql::reset(self::FROM);
            sql::raw(self::FROM)->add(self::FROM . ' ( ' . $table . ' ) ' . $alias);
        }else {
            throw new \Exception( 'From table must be of instance of table or sql' );
        }

        return $this;
    }

    /**
     * First
     *
     * @param int $style
     * @return mixed
     */
    public function first($style = \PDO::FETCH_ASSOC)
    {
        return $this->execute()->fetch($style);
    }

    /**
     * Group By
     *
     * @param array $columns
     * @return $this
     */
    public function group(array $columns)
    {
        foreach($columns as $key => $column) {
            if (empty(sql::raw(self::GROUP)->sql())){
                sql::raw(self::GROUP)->add(self::GROUP . ' ' . $this->addAccent($column));
            }else{
                sql::raw(self::GROUP)->add(', ' . $this->addAccent($column));
            }
        }
        return $this;
    }

    /**
     * Inner Join
     *
     * @param array $join
     * @param array $cols
     * @return $this
     * @throws \Exception
     */
    public function innerJoin(array $join, array $cols = [self::SQL_STAR])
    {
        $join = parent::join($join,null,self::INNER_JOIN);

        //Add cols we want for this table
        if($join) {
            foreach ($join as $key => $table) {
                self::cols($cols, $key, $table);
            }
        }

        return $this;
    }

    /**
     * Instance
     *
     * @param table $table
     * @return mixed
     */
    public static function instance(table $table)
    {
        if (!isset(static::$instances[self::SELECT.'_'.$table->name()])) {
            self::$instances[self::SELECT.'_'.$table->name()] = new self($table, self::SELECT);
        }
        return self::$instances[self::SELECT.'_'.$table->name()];
    }

    /**
     * Left Join
     *
     * @param array $join
     * @param $on
     * @param array $cols
     * @return $this
     * @throws \Exception
     */
    public function leftJoin(array $join, $on, array $cols = [self::SQL_STAR])
    {
        $leftJoin = parent::join($join,$on,self::LEFT_JOIN);

        //Add cols we want for this table
        if( $leftJoin ) {
            foreach ( $join as $key => $table ) {
                self::cols( $cols, $key, $table );
            }
        }
        
        return $this;
    }

    /**
     * Limit
     *
     * @param $offset
     * @return $this
     */
    public function limit($offset)
    {
        sql::reset(self::LIMIT);
        sql::raw(self::LIMIT)->add(self::LIMIT . ' ' . $offset);
        return $this;
    }

    /**
     * Many
     *
     * @param int $style
     * @return mixed
     */
    public function many($style = \PDO::FETCH_ASSOC)
    {
        return $this->execute()->fetchAll($style);
    }

    /**
     * Order By
     *
     * @param array $columns
     * @return $this
     */
    public function orderCol(array $columns)
    {
        foreach ($columns as $column => $type) {
            if ($this->multipleOrders) {
                sql::raw(self::ORDER)->add(', ' . $this->addAccent($column) . ' ' . $type);
            }else {
                sql::raw(self::ORDER)->add(self::ORDER .  ' ' . $this->addAccent($column) . ' ' . $type);
                $this->multipleOrders = true;
            }
        }

        return $this;
    }

    /**
     * Or Where Statement
     *
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        parent::where($column, $operator, $value, self::OR_WHERE);
        return $this;
    }

    /**
     * Or Where In
     *
     * @param $column
     * @param array $in
     * @return $this
     */
    public function orWhereIn($column, array $in)
    {
        parent::whereIn($column, $in, $clause = self::OR_WHERE);

        return $this;
    }

    /**
     * Right Join
     *
     * @param array $join
     * @param $on
     * @param array $cols
     * @return $this
     * @throws \Exception
     */
    public function rightJoin(array $join, $on, array $cols = [self::SQL_STAR])
    {
        $rightJoin = parent::join($join,$on,self::RIGHT_JOIN);

        //Add cols we want for this table
        if( $rightJoin ) {
            foreach ( $join as $key => $table ) {
                self::cols( $cols, $key, $table );
            }
        }

        return $this;
    }

    /**
     * Select
     *
     * @param bool|false $distinct
     * @return $this
     */
    public function select($distinct = false)
    {
        sql::raw(self::SELECT)->add(self::SELECT);

        //Distinct
        if($distinct){
            sql::raw(self::SELECT)->add(self::DISTINCT);
        }

        return $this;
    }

    /**
     * Where
     *
     * @param $column
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        parent::where($column, $operator, $value, self::WHERE);
        return $this;
    }

    /**
     * Where In
     *
     * @param $column
     * @param array $in
     * @return $this
     */
    public function whereIn($column, array $in)
    {
        parent::whereIn($column, $in, $clause = self::WHERE);
        return $this;
    }
}
