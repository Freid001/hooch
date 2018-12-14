<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNot
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereNot
{
    use Common;

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return $this
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(null, null, $this->logical()->omitTrailingSpace()->not($this->accent()->append($column,'.'), $comparison, $logical));
        }

        return $this;
    }
}
