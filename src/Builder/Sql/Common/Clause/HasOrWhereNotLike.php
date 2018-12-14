<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereNotLike
{
    use Common;

    /**
     * @param $column
     * @param $values
     * @return $this
     */
    public function orWhereNotLike($column, $values)
    {
        if ($this instanceof FilterInterface) {
            $this->orWhereNot($column, null, $this->logical()->omitTrailingSpace()->like($values));
        }

        return $this;
    }
}
