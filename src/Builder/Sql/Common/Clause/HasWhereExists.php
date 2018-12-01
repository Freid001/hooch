<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereExists
{
    use Common;

    /**
     * @param Sql $subQuery
     * @return $this
     */
    public function whereExists(Sql $subQuery)
    {
        if($this instanceof FilterInterface) {
            $this->where(null, null, $this->logical()->omitTrailingSpace()->exists($subQuery));
        }

        return $this;
    }
}
