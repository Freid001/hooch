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

        $this->queryAdd(self::SELECT, new Sql(self::SELECT));
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
    public function cols($cols = [self::SQL_STAR], $alias = null) : SelectInterface
    {
        $i = 0;
        foreach ($cols as $key => &$col) {
            if ((int)$key !== $i) {
                $i++; //Increment only when we using int positions
            }

            $sql = $this->columnClause(
                ($col !== self::SQL_STAR) ? $this->addAccent($col) : $col,
                !empty($alias) ? $this->addAccent($alias) : $alias,
                ($key !== $i) ? $key : null,
                !empty($this->queryGet(self::COLS))
            );

            $this->queryAdd(self::COLS, $sql);
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
        $this->queryAdd(self::FROM, $this->fromClause($table, $alias));

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
            $this->queryAdd(self::JOIN, $this->joinClause(self::LEFT_JOIN, $table, $alias));
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
        $this->queryAdd(self::JOIN, $this->onClause($first, $operator, $second, self::ON));

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
        $this->queryAdd(self::JOIN, $this->onClause($first, $operator, $second, self:: OR));

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
     * @param string|null $operator
     * @param string|null $value
     * @param string $clause
     * @return SelectInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE) : SelectInterface
    {
        $this->filter->where($column, $operator, $value, $clause);

        return $this;
    }

    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return SelectInterface
     */
    public function orWhere($column, $operator = null, $value = null) : SelectInterface
    {
        $this->filter->orWhere($column, $operator, $value);

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
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, $alias = null) : SelectInterface
    {
        $sql = $this->groupByClause(
            $this->addAccent($column),
            !empty($alias) ? $this->addAccent($alias) : $alias,
            !empty($this->queryGet(self::GROUP))
        );

        $this->queryAdd(self::GROUP,$sql);

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
            !empty($this->queryGet(self::ORDER))
        );

        $this->queryAdd(self::ORDER,$sql);

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
        $this->queryAdd(self::LIMIT,$this->limitClause($limit));

        return $this;
    }

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset($offset) : SelectInterface
    {
        $this->queryAdd(self::OFFSET,$this->offsetClause($offset));

        return $this;
    }

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, $all = false) : SelectInterface
    {
        $this->queryAdd(self::UNION, $this->unionClause($select, $all));

        return $this;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        self::SELECT,   // DONE
        self::COLS,     // DONE
        self::FROM,     // DONE
        self::JOIN,     // <<<
        self::WHERE,    // DONE
        self::GROUP,    // DONE
        self::ORDER,    // DONE
        self::HAVING,   // <<<
        self::LIMIT,    // DONE
        self::OFFSET,   // DONE
        self::UNION     // DONE
    ]) : Sql
    {
        if (in_array(self::WHERE, $clauses)) {
            $this->queryAdd(self::WHERE, $this->filter->build([self::WHERE]));
        }

        $sql = $this->queryBuild($clauses);

        $this->queryReset($clauses);

        return $sql;
    }
}
