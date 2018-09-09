<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasLimit
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasLimit
{
    use Common;

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->query()->add(Sql::LIMIT, $this->limitClause($limit));

        return $this;
    }

    /**
     * @param int $limit
     * @return Sql
     */
    private function limitClause(int $limit)
    {
        return new Sql(Sql::LIMIT.' '.$limit);
    }
}
