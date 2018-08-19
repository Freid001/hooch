<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Sql;

/**
 * Interface WhereExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return mixed
     */
    public function whereExists(Sql $subQuery);
}