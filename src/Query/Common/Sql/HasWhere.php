<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasWhere
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhere
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function where(?string $column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->query()->sql()
                ->ifThenAppend($this->noClause(), $this->whereJoin())
                ->ifThenAppend($this->isNested($operator), Sql::SQL_BRACKET_OPEN)
                ->ifThenAppend($this->noClause(), $this->query()->accent()->append($column, '.'));

            if($this->isAnd($operator)) {
                $this->query()->sql()
                    ->append($this->operator()->logical()->column()->omitTrailingSpace()->and(
                        $column,
                        $operator
                    )->build());
            }else {
                $this->query()->sql()
                    ->append($operator->build());
            }

            $this->query()->appendSqlToClause($this->whereJoin());

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }

    /**
     * @param OperatorInterface $operator
     * @return bool
     */
    private function isNested(OperatorInterface $operator): bool
    {
        if($this instanceof FilterInterface) {
            if ($this->query()->hasClause($this->whereJoin())) {
                return false;
            }

            if (in_array($operator->getOperator(), [Sql:: AND, Sql:: OR])) {
                return false;
            }

            return $this->operator()->logical()->column()->getNested();
        }

        return false;
    }

    /**
     * @param OperatorInterface $operator
     * @return bool
     */
    private function isAnd(OperatorInterface $operator): bool
    {
        if ($this instanceof FilterInterface &&
                $this->query()->hasClause($this->whereJoin())) {

            if ($operator->getOperator() !== Sql:: OR) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function noClause(): bool
    {
        if ($this instanceof FilterInterface &&
            !$this->query()->hasClause($this->whereJoin())) {
                return true;
        }

        return false;
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
