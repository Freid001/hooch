<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereNotLike
{
    use Common;

    /**
     * @param $column
     * @param $values
     * @return $this
     */
    public function whereNotLike($column, $values)
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, null, $this->logical()->like($values));
        }

        return $this;
    }
}
