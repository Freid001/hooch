<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereNotExists
{
    use Common;

    /**
     * @param Sql $subQuery
     * @return $this
     */
    public function orWhereNotExists(Sql $subQuery)
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot(null, null, $this->logical()->exists($subQuery));
        }

        return $this;
    }
}
