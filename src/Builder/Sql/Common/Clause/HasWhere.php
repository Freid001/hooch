<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnInterface;

/**
 * Trait HasWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhere
{
    use Common;

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return $this
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        $column = $this->accent()->append($column, '.');

        $sql = new Sql();
        $sql = $this->appendBracket($sql, $logical);
        $sql = $this->appendColumn($sql, $column);
        $sql = $this->appendAnd($sql, $column, $comparison, $logical);

        $sql->append($this->logical()->omitTrailingSpace());

        if(empty($this->logical()->getOperator())) {
            $sql->appendIf(!is_null($comparison), $comparison);
            $sql->appendIf(!is_null($logical), $logical);
        }

        $this->query()->add($this->whereJoin(), $sql);

        return $this;
    }

    /**
     * @param Logical $logical
     * @return string|null
     */
    private function operator(?Logical $logical)
    {
        $operator = null;
        if (!is_null($logical)) {
            $operator = $logical->getOperator();
        }

        if (!empty($this->query()->get(Sql::WHERE))) {
            if ($operator !== Sql:: OR) {
                $operator = Sql:: AND;
            }
        }

        return $operator;
    }

    /**
     * @param Sql $sql
     * @param Logical|null $logical
     * @return Sql
     */
    private function appendBracket(Sql $sql, ?Logical $logical)
    {
        if (!empty($this->query()->get(Sql::WHERE))){
            return $sql;
        }

        if(in_array($this->operator($logical), [Sql:: AND, Sql:: OR])){
            return $sql;
        }

        return $sql->append($this->whereJoin())->appendIf(
            $this->logical()->getNested(),
            Sql::SQL_BRACKET_OPEN
        );
    }

    /**
     * @param Sql $sql
     * @param $column
     * @return Sql
     */
    private function appendColumn(Sql $sql, $column)
    {
        if (empty($this->query()->get(Sql::WHERE))) {
            return $sql->append($column);
        }

        return $sql;
    }

    /**
     * @param Sql $sql
     * @param $column
     * @param Comparison|null $comparison
     * @param Logical|null $logical
     * @return Sql
     */
    private function appendAnd(Sql $sql, $column, ?Comparison $comparison, ?Logical $logical)
    {
        if ($this->operator($logical) !== Sql:: AND) {
            return $sql;
        }

        $this->logical()->and(
            $column,
            $comparison,
            $logical
        );

        return $sql;
    }

    /**
     * @return string
     */
    private function whereJoin()
    {
        if ($this instanceof OnInterface) {
            return Sql::JOIN;
        } else {
            return Sql::WHERE;
        }
    }
}
