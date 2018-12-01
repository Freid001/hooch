<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereNotExists
{
    use Common;

    /**
     * @param Sql $subQuery
     * @return $this
     */
    public function whereNotExists(Sql $subQuery)
    {
        if($this instanceof FilterInterface) {
            $this->whereNot(null, null, $this->logical()->omitTrailingSpace()->exists($subQuery));
        }

        return $this;
    }
}
