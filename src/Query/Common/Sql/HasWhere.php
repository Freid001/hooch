<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasWhere
 * @package QueryMule\Query\Common\Sql
 */
trait HasWhere
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function where($column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $column = $this->query()->accent()->append($column, '.');

            $sql = $this->query()->sql();
            $sql = $this->appendBracket($sql, $operator);
            $sql = $this->appendColumn($sql, $column);
            $sql = $this->appendAnd($sql, $column, $operator);

            $sql->append($this->query()->logical()->omitTrailingSpace());

            if (empty($this->query()->logical()->getOperator())) {
                $sql->append($operator);
            }

            $this->query()->append($this->whereJoin(), $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }

    /**
     * @param Sql $sql
     * @param OperatorInterface $operator
     * @return Sql
     */
    private function appendBracket(Sql $sql, OperatorInterface $operator): Sql
    {
        if($this instanceof FilterInterface) {
            if ($this->query()->hasClause($this->whereJoin())) {
                return $sql;
            }

            if (in_array($operator->getOperator(), [Sql:: AND, Sql:: OR])) {
                return $sql;
            }

            return $sql->append($this->whereJoin())->ifThenAppend(
                $this->query()->logical()->getNested(),
                Sql::SQL_BRACKET_OPEN
            );
        }

        return $sql;
    }

    /**
     * @param Sql $sql
     * @param $column
     * @return Sql
     */
    private function appendColumn(Sql $sql, $column): Sql
    {
        if($this instanceof FilterInterface &&
            !$this->query()->hasClause($this->whereJoin())) {
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
        if ($this instanceof FilterInterface &&
                $this->query()->hasClause($this->whereJoin())) {
            if ($operator->getOperator() !== Sql:: OR) {
                $this->query()->logical()->and(
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
