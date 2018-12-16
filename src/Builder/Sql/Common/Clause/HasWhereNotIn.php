<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;

use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereNotIn
{
    use Common;

    /**
     * @param $column
     * @param array $values
     * @return $this
     */
    public function whereNotIn($column, array $values = [])
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, $this->logical()->omitTrailingSpace()->in($values));
        }

        return $this;
    }
}
