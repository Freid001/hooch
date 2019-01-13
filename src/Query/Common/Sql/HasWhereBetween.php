<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereBetween
 * @package QueryMule\Query\Common\Sql
 */
trait HasWhereBetween
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereBetween($column, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where($column, $this->query()->logical()->omitTrailingSpace()->between($from, $to));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}