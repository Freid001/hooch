<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Class HasLimitClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasLimitClause
{
    /**
     * @param int $limit
     * @return Sql
     */
    final protected function limitClause(int $limit)
    {
        return new Sql(Sql::LIMIT.' '.$limit);
    }
}
