<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereIn
{
    use Common;

    /**
     * @param $column
     * @param array $values
     * @return $this
     */
    public function whereIn($column, array $values = [])
    {
        if($this instanceof FilterInterface) {
            $this->where($column, $this->logical()->omitTrailingSpace()->in($values));
        }

        return $this;
    }
}
