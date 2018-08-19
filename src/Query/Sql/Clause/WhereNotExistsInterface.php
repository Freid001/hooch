<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Sql;

/**
 * Interface WhereNotExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return mixed
     */
    public function whereNotExists(Sql $subQuery);
}