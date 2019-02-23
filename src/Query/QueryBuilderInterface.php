<?php

namespace Redstraw\Hooch\Query;


use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;

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