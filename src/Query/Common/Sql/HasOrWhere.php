<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhere
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrWhere
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhere($column, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(null, $this->query()->logical()->omitTrailingSpace()->or($this->query()->accent()->append($column,'.'), $operator));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
