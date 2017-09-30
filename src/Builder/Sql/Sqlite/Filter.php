<?php

namespace QueryMule\Builder\Sql\Sqlite;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

class Filter implements FilterInterface
{
    use Accent;
    use Query;

    use HasWhereClause;

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
            $clause = self::AND_WHERE;
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
                $this->queryAdd(self::WHERE, new Sql(self::OR_WHERE));
            }

            $this->queryAdd(self::WHERE, new Sql("("));
            $this->nestedWhereClause($column);
            $this->queryAdd(self::WHERE, new Sql(")"));

            return $this;
        }

        $this->queryAdd(self::WHERE,$this->whereClause($column,$operator,$value,self::OR_WHERE));

        return $this;
    }

    /**
     * @return \QueryMule\Query\Sql\Sql
     */
    public function build() : Sql
    {
        $sql = $this->queryBuild([
            self::WHERE
        ]);

        $this->queryReset();

        return $sql;
    }
}