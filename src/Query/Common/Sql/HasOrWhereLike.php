<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereLike
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrWhereLike
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereLike($column, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere($column, $this->query()->logical()->omitTrailingSpace()->like($value));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
