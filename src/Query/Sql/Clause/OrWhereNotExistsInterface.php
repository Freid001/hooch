<?php

namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereNotBetweenInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereNotExists(Sql $subQuery);
}