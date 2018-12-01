<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class HasOrWhereNotBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereNotBetween
{
    use Common;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return $this
     */
    public function whereNotBetween($column, $from, $to)
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, null, $this->logical()->omitTrailingSpace()->between($from, $to));
        }

        return $this;
    }
}
