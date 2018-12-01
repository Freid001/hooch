<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhere
{
    use Common;

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return $this
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        if($this instanceof FilterInterface) {
            $this->where(null, null, $this->logical()->omitTrailingSpace()->or($this->accent()->append($column,'.'), $comparison, $logical));
        }

        return $this;
    }
}
