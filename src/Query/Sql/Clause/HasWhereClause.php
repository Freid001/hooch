<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasWhereClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasWhereClause
{
    /**
     * @var bool
     */
    protected $nestedWhere = false;

    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @param string|null $clause
     * @return Sql
     * @throws SqlException
     */
    final protected function whereClause($column, $operator = null, $value = null, $clause = FilterInterface::WHERE)
    {
        $sql = null;
        switch ($clause) {
            case FilterInterface::WHERE:
                $sql .= FilterInterface::WHERE.SelectInterface::SQL_SPACE;
                $sql .= $this->bracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::AND:
                $sql .= FilterInterface::AND.SelectInterface::SQL_SPACE;
                $sql .= $this->bracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::OR:
                $sql .= FilterInterface::OR.SelectInterface::SQL_SPACE;
                $sql .= $this->bracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;
        }

        return new Sql($sql, [$value]);
    }

    /**
     * @param \Closure $column
     * @return Sql
     */
    final protected function nestedWhereClause(\Closure $column)
    {
        $this->nestedWhere = true;
        $column($this);
        $this->nestedWhere = true;
        return new Sql($this->bracket(false));
    }

    /**
     * @param $open
     * @return string
     */
    private function bracket($open)
    {
        $bracket = SelectInterface::SQL_BRACKET_CLOSE;
        if ($this->nestedWhere) {
            if ($open == 'open') {
                $bracket = SelectInterface::SQL_BRACKET_OPEN.SelectInterface::SQL_SPACE;
            }

            $this->nestedWhere = false;

            return $bracket;
        }

        return null;
    }
}
