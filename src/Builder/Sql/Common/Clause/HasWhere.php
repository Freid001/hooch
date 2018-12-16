<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhere
{
    use Common;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return $this
     */
    public function where($column, OperatorInterface $operator)
    {
        $column = $this->accent()->append($column, '.');

        $sql = new Sql();
        $sql = $this->appendBracket($sql, $operator);
        $sql = $this->appendColumn($sql, $column);
        $sql = $this->appendAnd($sql, $column, $operator);

        $sql->append($this->logical()->omitTrailingSpace());

        if(empty($this->logical()->getOperator())) {
            $sql->append($operator);
        }

        $this->query()->add($this->whereJoin(), $sql);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param OperatorInterface $operator
     * @return Sql
     */
    private function appendBracket(Sql $sql, OperatorInterface $operator): Sql
    {
        if (!empty($this->query()->get($this->whereJoin()))){
            return $sql;
        }

        if(in_array($operator->getOperator(), [Sql:: AND, Sql:: OR])){
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
    private function appendColumn(Sql $sql, $column): Sql
    {
        if (empty($this->query()->get($this->whereJoin()))) {
            return $sql->append($column);
        }

        return $sql;
    }

    /**
     * @param Sql $sql
     * @param $column
     * @param OperatorInterface $operator
     * @return Sql
     */
    private function appendAnd(Sql $sql, $column, OperatorInterface $operator): Sql
    {
        if (!empty($this->query()->get($this->whereJoin()))) {
            if ($operator->getOperator() !== Sql:: OR) {
                $this->logical()->and(
                    $column,
                    $operator
                );
            }
        }

        return $sql;
    }

    /**
     * @return string
     */
    private function whereJoin(): String
    {
        if ($this instanceof OnFilterInterface) {
            return Sql::JOIN;
        } else {
            return Sql::WHERE;
        }
    }
}
