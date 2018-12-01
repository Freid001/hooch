<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereIn
{
   use Common;

    /**
     * @param $column
     * @param array $values
     * @return $this
     */
    public function orWhereIn($column, array $values = [])
    {
        if($this instanceof FilterInterface) {
            $this->orWhere($column, null, $this->logical()->omitTrailingSpace()->in($values));
        }

        return $this;
    }
}
