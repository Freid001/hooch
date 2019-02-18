<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNot
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNot
{
    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNot(?string $column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(
                null,
                $this->operator()
                    ->logical()
                    ->column()
                    ->omitTrailingSpace()
                    ->not($column, $operator)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
