<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Interface WhereNotExistsInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotExistsInterface
{
    /**
     * @param Sql $subQuery
     * @return SelectInterface|FilterInterface|QueryBuilderInterface
     */
    public function whereNotExists(Sql $subQuery);
}