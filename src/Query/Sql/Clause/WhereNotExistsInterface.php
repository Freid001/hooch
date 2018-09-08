<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereNotExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery);
}