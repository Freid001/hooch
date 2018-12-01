<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class HasWhereBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereBetween
{
    use Common;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return $this
     */
    public function whereBetween($column, $from, $to)
    {
        if($this instanceof FilterInterface) {
            $this->where($column, null, $this->logical()->omitTrailingSpace()->between($from, $to));
        }

        return $this;
    }
}