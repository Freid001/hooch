<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNot
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereNot
{
    use Common;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return $this
     */
    public function whereNot($column, OperatorInterface $operator)
    {
        if($this instanceof FilterInterface) {
            $this->where(null, $this->logical()->omitTrailingSpace()->not($this->accent()->append($column,'.'), $operator));
        }

        return $this;
    }
}
