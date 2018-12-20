<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\QueryBuilderInterface;
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
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return $this
     */
    public function union(QueryBuilderInterface $select, bool $all = false)
    {
        $this->query()->append(Sql::UNION, $this->unionClause($select, $all));

        return $this;
    }

    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return Sql
     */
    private function unionClause(QueryBuilderInterface $select, bool $all = false): Sql
    {
        $query = $select->build();

        $sql = new Sql(Sql::UNION,$query->parameters());
        $sql->ifThenAppend(!empty($all),Sql::ALL);
        $sql->append($query->sql());

        return $sql;
    }
}
