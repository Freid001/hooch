<?php

namespace QueryMule\Builder\Sql\Generic;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasColumnClause;
use QueryMule\Query\Sql\Clause\HasFromClause;
use QueryMule\Query\Sql\Clause\HasGroupByClause;
use QueryMule\Query\Sql\Clause\HasJoinClause;
use QueryMule\Query\Sql\Clause\HasLimitClause;
use QueryMule\Query\Sql\Clause\HasOffsetClause;
use QueryMule\Query\Sql\Clause\HasOrderByClause;
use QueryMule\Query\Sql\Clause\HasUnionClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Sql\Operator\Comparison;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
class Select implements SelectInterface
{
    use Accent;
    use Query;
    use HasFromClause;
    use HasColumnClause;
    use HasJoinClause;
    use HasGroupByClause;
    use HasOrderByClause;
    use HasLimitClause;
    use HasOffsetClause;
    use HasUnionClause;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     * @param string|null $accent
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null, $accent = null)
    {
        if (!empty($cols)) {
            $this->cols($cols);
        }

        if (!empty($table)) {
            $this->from($table);
        }

        if (!empty($accent)) {
            $this->setAccent($accent);
        }

        $this->queryAdd(Sql::SELECT, new Sql(Sql::SELECT));
    }

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true) : SelectInterface
    {
        $this->ignoreAccentSymbol($ignore);

        if (!empty($this->filter)) {
            $this->filter->ignoreAccent($ignore);
        }

        return $this;
    }

    /**
     * @param array $cols
     * @param null $alias
     * @return SelectInterface
     */
    public function cols($cols = [Sql::SQL_STAR], $alias = null) : SelectInterface
    {
        $i = 0;
        foreach ($cols as $key => &$col) {
            if ((int)$key !== $i) {
                $i++; //Increment only when we using int positions
            }

            $sql = $this->columnClause(
                ($col !== Sql::SQL_STAR) ? $this->addAccent($col) : $col,
                !empty($alias) ? $this->addAccent($alias) : $alias,
                ($key !== $i) ? $key : null,
                !empty($this->queryGet(Sql::COLS))
            );

            $this->queryAdd(Sql::COLS, $sql);
        }

        return $this;
    }

    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null) : SelectInterface
    {
        $this->queryAdd(Sql::FROM, $this->fromClause($table, $alias));

        $this->filter = $table->filter();

        return $this;
    }

//    public function join(array $table, \Closure $on) {}

    /**
     * @param array $table
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     * @throws SqlException
     */
    public function leftJoin(array $table, $first, $operator = null, $second = null) : SelectInterface
    {
        $keys = array_keys($table);

        $alias = isset($keys[0]) ? $keys[0] : null;
        $table = isset($table[$keys[0]]) ? $table[$keys[0]] : null;

        if ($table instanceof RepositoryInterface) {
            $this->queryAdd(Sql::JOIN, $this->joinClause(Sql::JOIN_LET, $table, $alias));
            return $this->on($first, $operator, $second);
        } else {
            throw new SqlException('Table must be instance of RepositoryInterface');
        }
    }

    /**
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     */
    public function on($first, $operator, $second) : SelectInterface
    {
        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql::ON));

        return $this;
    }

    /**
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     */
    public function orOn($first, $operator = null, $second = null) : SelectInterface
    {
        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql:: OR));

        return $this;
    }

//    /**
//     * @return SelectInterface
//     */
//    public function rightJoin() : SelectInterface
//    {
//        $this->queryAdd(self::JOIN, new Sql('',[]));
//
//        return $this;
//    }
//
//    /**
//     * @return SelectInterface
//     */
//    public function crossJoin() : SelectInterface
//    {
//        $this->queryAdd(self::JOIN, new Sql('',[]));
//
//        return $this;
//    }
//
//    /**
//     * @return SelectInterface
//     */
//    public function innerJoin() : SelectInterface
//    {
//        $this->queryAdd(self::JOIN, new Sql('',[]));
//
//        return $this;
//    }
//
//    /**
//     * @return SelectInterface
//     */
//    public function outerJoin() : SelectInterface
//    {
//        $this->queryAdd(self::JOIN, new Sql('',[]));
//
//        return $this;
//    }

    /**
     * @param string $column
     * @param null|Comparison|null $comparison
     * @param null $value
     * @param string $logical
     * @return SelectInterface
     */
    public function where($column, ?Comparison $comparison = null, $value = null, $logical = Sql::WHERE) : SelectInterface
    {
        $this->filter->where($column, $comparison, $value, $logical);

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison|null $comparison
     * @param null $value
     * @return SelectInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, $value = null) : SelectInterface
    {
        $this->filter->orWhere($column, $comparison, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return SelectInterface
     */
    public function whereIn($column, array $values = []) : SelectInterface
    {
        $this->filter->whereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return SelectInterface
     */
    public function orWhereIn($column, array $values = []) : SelectInterface
    {
        $this->filter->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null $value
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, $value = null) : FilterInterface
    {
        $this->filter->whereNot($column, $comparison, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, $alias = null) : SelectInterface
    {
        $sql = $this->groupByClause(
            $this->addAccent($column),
            !empty($alias) ? $this->addAccent($alias) : $alias,
            !empty($this->queryGet(Sql::GROUP))
        );

        $this->queryAdd(Sql::GROUP,$sql);

        return $this;
    }

    /**
     * @param string $column
     * @param string $sort
     * @param null $alias
     * @return SelectInterface
     */
    public function orderBy($column, $sort = 'desc', $alias = null) : SelectInterface
    {
        $sql = $this->orderByClause(
            $this->addAccent($column),
            $sort,
            !empty($alias) ? $this->addAccent($alias) : $alias,
            !empty($this->queryGet(Sql::ORDER))
        );

        $this->queryAdd(Sql::ORDER,$sql);

        return $this;
    }

    public function having()
    {}

    /**
     * @param int $limit
     * @return SelectInterface
     */
    public function limit($limit) : SelectInterface
    {
        $this->queryAdd(Sql::LIMIT,$this->limitClause($limit));

        return $this;
    }

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset($offset) : SelectInterface
    {
        $this->queryAdd(Sql::OFFSET,$this->offsetClause($offset));

        return $this;
    }

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, $all = false) : SelectInterface
    {
        $this->queryAdd(Sql::UNION, $this->unionClause($select, $all));

        return $this;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::SELECT,   // DONE
        Sql::COLS,     // DONE
        Sql::FROM,     // DONE
        Sql::JOIN,     // <<<
        Sql::WHERE,    // DONE
        Sql::GROUP,    // DONE
        Sql::ORDER,    // DONE
        Sql::HAVING,   // <<<
        Sql::LIMIT,    // DONE
        Sql::OFFSET,   // DONE
        Sql::UNION     // DONE
    ]) : Sql
    {
        if (in_array(Sql::WHERE, $clauses)) {
            $this->queryAdd(Sql::WHERE, $this->filter->build([Sql::WHERE]));
        }

        $sql = $this->queryBuild($clauses);

        $this->queryReset($clauses);

        return $sql;
    }
}
