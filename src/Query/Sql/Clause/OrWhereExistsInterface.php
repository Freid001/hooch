<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Interface OrWhereExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return mixed
     */
    public function orWhereExists(Sql $subQuery);
}