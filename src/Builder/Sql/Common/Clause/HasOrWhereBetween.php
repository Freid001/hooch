<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereBetween
{
    use Common;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return $this
     */
    public function orWhereBetween($column, $from, $to)
    {
        if($this instanceof FilterInterface) {
            $this->orWhere($column, $this->logical()->omitTrailingSpace()->between($from, $to));
        }

        return $this;
    }
}
