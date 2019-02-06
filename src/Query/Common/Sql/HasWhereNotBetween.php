<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereNotBetween
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNotBetween($column, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, $this->query()->logical()->omitTrailingSpace()->between($from, $to));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
