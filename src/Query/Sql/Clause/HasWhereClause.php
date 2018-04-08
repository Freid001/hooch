<?php


namespace QueryMule\Query\Sql\Clause;

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
     * @param string|Sql|null $operator
     * @param string|null $value
     * @param string $clause
     * @return Sql
     */
    final protected function whereClause($column, $operator = null, $value = null, $clause = FilterInterface::WHERE)
    {
        if($operator instanceof Sql) {
            $value = $operator->parameters();
            $operator = $operator->sql();
        }

        $sql = null;
        switch ($clause) {
            case FilterInterface::WHERE:
                $sql .= FilterInterface::WHERE.SelectInterface::SQL_SPACE;
                $sql .= $this->nestedBracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::AND:
                $sql .= FilterInterface::AND.SelectInterface::SQL_SPACE;
                $sql .= $this->nestedBracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::OR:
                $sql .= FilterInterface::OR.SelectInterface::SQL_SPACE;
                $sql .= $this->nestedBracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::NOT:
                $sql .= FilterInterface::NOT.SelectInterface::SQL_SPACE;
                $sql .= $this->nestedBracket(true);
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    $column,
                    $operator
                ]);
                break;

            case FilterInterface::IN:
                $sql .= FilterInterface::IN.SelectInterface::SQL_SPACE;
                $sql .= implode(SelectInterface::SQL_SPACE, [
                    SelectInterface::SQL_BRACKET_OPEN,
                    implode( ",", array_fill(0, count($value), "?")),
                    SelectInterface::SQL_BRACKET_CLOSE
                ]);
                break;
        }

        return new Sql($sql, !is_array($value) ? [$value] : $value);
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
        return new Sql($this->nestedBracket(false));
    }

    /**
     * @param bool $open
     * @return string
     */
    private function nestedBracket($open)
    {
        $bracket = SelectInterface::SQL_BRACKET_CLOSE;
        if ($this->nestedWhere) {
            if ($open) {
                $bracket = SelectInterface::SQL_BRACKET_OPEN.SelectInterface::SQL_SPACE;
            }

            $this->nestedWhere = false;

            return $bracket;
        }

        return null;
    }
}
