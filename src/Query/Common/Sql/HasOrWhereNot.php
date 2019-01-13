<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNot
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrWhereNot
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNot($column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(null, $this->query()->logical()->omitTrailingSpace()->not($this->query()->accent()->append($column,'.'), $operator));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
