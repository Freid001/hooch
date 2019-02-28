<?php

namespace Redstraw\Hooch\Query;


/**
 * Interface QueryBuilderInterface
 * @package Redstraw\Hooch\Query
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

    /**
     * @return Operator
     */
    public function operator(): Operator;
}