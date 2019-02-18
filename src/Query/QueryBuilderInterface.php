<?php

namespace Redstraw\Hooch\Query;


use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Interface FilterInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
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