<?php

declare(strict_types=1);

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
     * @param $value
     * @return $this
     */
    public function whereNotLike($column, $value)
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, $this->logical()->omitTrailingSpace()->like($value));
        }

        return $this;
    }
}
