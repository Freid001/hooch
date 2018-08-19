<?php

namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Interface OrWhereNotBetweenInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return mixed
     */
    public function orWhereNotExists(Sql $subQuery);
}