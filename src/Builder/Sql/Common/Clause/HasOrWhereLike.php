<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereLike
{
    use Common;

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function orWhereLike($column, $value)
    {

        if($this instanceof FilterInterface) {
            $this->orWhere($column, null, $this->logical()->omitTrailingSpace()->like($value));
        }

        return $this;
    }
}
