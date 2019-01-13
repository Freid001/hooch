<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotLike
 * @package QueryMule\Query\Common\Sql
 */
trait HasWhereNotLike
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNotLike($column, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, $this->query()->logical()->omitTrailingSpace()->like($value));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
