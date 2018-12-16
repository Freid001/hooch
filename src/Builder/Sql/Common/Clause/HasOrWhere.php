<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\OperatorInterface;
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
     * @param OperatorInterface $operator
     * @return $this
     */
    public function orWhere($column, OperatorInterface $operator)
    {
        if($this instanceof FilterInterface) {
            $this->where(null, $this->logical()->omitTrailingSpace()->or($this->accent()->append($column,'.'), $operator));
        }

        return $this;
    }
}
