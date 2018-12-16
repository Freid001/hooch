<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereExists
{
   use Common;

    /**
     * @param Sql $subQuery
     * @return $this
     */
    public function orWhereExists(Sql $subQuery)
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(null, $this->logical()->omitTrailingSpace()->exists($subQuery));
        }

        return $this;
    }
}
