<?php

namespace QueryMule\Builder\Sql\Sqlite;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasJoinClause;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

class Filter implements FilterInterface
{
    use Accent;
    use Query;

    use HasWhereClause;
    use HasJoinClause;

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        $this->setAccent("`");
    }

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true) : FilterInterface
    {
        $this->ignoreAccentSymbol($ignore);

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $clause
     * @return FilterInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE) : FilterInterface
    {
        if($clause == self::WHERE && !empty($this->queryGet(self::WHERE))) {
            $clause = self::AND;
        }

        $column = ($column instanceof \Closure) ? $column : $this->addAccent($column,'.');

        if($column instanceof \Closure) {
            if(!$this->ignoreWhereClause) {
                $this->queryAdd(self::WHERE, new Sql($clause));
            }

            $this->queryAdd(self::WHERE, new Sql("("));
            $this->nestedWhereClause($column);
            $this->queryAdd(self::WHERE, new Sql(")"));

            return $this;
        }

        $this->queryAdd(self::WHERE, $this->whereClause($column, $operator, $value, $clause));

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @return FilterInterface
     */
    public function orWhere($column, $operator = null, $value = null) : FilterInterface
    {
        $column = ($column instanceof \Closure) ? $column : $this->addAccent($column,'.');

        if($column instanceof \Closure) {
            if(!$this->ignoreWhereClause) {
                $this->queryAdd(self::WHERE, new Sql(self::OR));
            }

            $this->queryAdd(self::WHERE, new Sql("("));
            $this->nestedWhereClause($column);
            $this->queryAdd(self::WHERE, new Sql(")"));

            return $this;
        }

        $this->queryAdd(self::WHERE,$this->whereClause($column,$operator,$value,self::OR));

        return $this;
    }

    /**
     * @param array $table
     * @param null $first
     * @param null $operator
     * @param null $second
     * @return FilterInterface
     * @throws SqlException
     */
    public function leftJoin(array $table, $first = null, $operator = null, $second = null) : FilterInterface
    {
        $keys = array_keys($table);

        $alias = isset($keys[0]) ? $keys[0] : null;
        $table = isset($table[$keys[0]]) ? $table[$keys[0]] : null;

        if($table instanceof RepositoryInterface) {
            $this->queryAdd(self::JOIN,$this->joinClause(self::LEFT_JOIN,$table, $alias));
            return $this->on($first,$operator,$second);
        }else {
            throw new SqlException('Table must be instance of RepositoryInterface');
        }
    }

    /**
     * @param $first
     * @param null $operator
     * @param null $second
     * @return FilterInterface
     */
    public function on($first, $operator, $second) : FilterInterface
    {
        $this->queryAdd(self::JOIN,$this->onClause($first,$operator,$second, self::ON));

        return $this;
    }

    /**
     * @param $first
     * @param null $operator
     * @param null $second
     * @return FilterInterface
     */
    public function orOn($first, $operator = null, $second = null) : FilterInterface
    {
        return $this;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        self::JOIN,
        self::WHERE
    ]) : Sql
    {
        $sql = $this->queryBuild($clauses);

        //$this->queryReset();

        return $sql;
    }
}