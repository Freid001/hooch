<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasUnion
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasUnion
{
    use Common;

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return $this
     */
    public function union(SelectInterface $select, bool $all = false)
    {
        $this->query()->add(Sql::UNION, $this->unionClause($select, $all));

        return $this;
    }

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return Sql
     */
    private function unionClause(SelectInterface $select, $all = false)
    {
        $query = $select->build();

        $sql = '';
        $sql .= Sql::UNION;
        $sql .= !empty($all) ? ' '.Sql::ALL.' ' : ' ';
        $sql .= $query->sql();

        return new Sql($sql,$query->parameters());
    }
}
