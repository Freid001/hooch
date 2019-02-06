<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNot
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereNot
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNot($column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(null, $this->query()->logical()->omitTrailingSpace()->not($this->query()->accent()->append($column,'.'), $operator));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
