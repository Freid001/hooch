<?php

namespace QueryMule\Query;


use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface QueryBuilderInterface
{
    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql;

    /**
     * @return Query
     */
    public function query(): Query;
}