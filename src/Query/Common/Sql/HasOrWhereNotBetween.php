<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotBetween
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrWhereNotBetween
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNotBetween($column, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot($column, $this->query()->logical()->omitTrailingSpace()->between($from, $to));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
