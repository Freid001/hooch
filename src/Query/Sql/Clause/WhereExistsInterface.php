<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery);
}