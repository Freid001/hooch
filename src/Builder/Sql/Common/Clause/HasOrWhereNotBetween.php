<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereNotBetween
{
    use Common;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return $this
     */
    public function orWhereNotBetween($column, $from, $to)
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot($column, null, $this->logical()->omitTrailingSpace()->between($from, $to));
        }

        return $this;
    }
}
