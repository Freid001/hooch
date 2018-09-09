<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasOffset
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOffset
{
    use Common;

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset)
    {
        $this->query()->add(Sql::OFFSET, $this->offsetClause($offset));

        return $this;
    }

    /**
     * @param int $offset
     * @return Sql
     */
    private function offsetClause(int $offset)
    {
        return new Sql(Sql::OFFSET.' '.$offset);
    }
}
