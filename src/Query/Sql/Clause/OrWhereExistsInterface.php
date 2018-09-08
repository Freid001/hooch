<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery);
}