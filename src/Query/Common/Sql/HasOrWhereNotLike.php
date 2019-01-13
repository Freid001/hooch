<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotLike
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrWhereNotLike
{
    /**
     * @param $column
     * @param $values
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNotLike($column, $values): FilterInterface
    {
        if ($this instanceof FilterInterface) {
            $this->orWhereNot($column, $this->query()->logical()->omitTrailingSpace()->like($values));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
