<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasFrom
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasFrom
{
    use Common;

    /**
     * @param FilterInterface $filter
     * @return mixed
     */
    abstract public function setFilter(FilterInterface $filter);

    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return $this
     */
    public function from(RepositoryInterface $table, $alias = null)
    {
        $this->query()->add(Sql::FROM, $this->fromClause($table, $alias));

        $this->setFilter($table->filter());

        return $this;
    }

    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return Sql
     */
    private function fromClause(RepositoryInterface $table, $alias = null)
    {
        $sql = '';
        $sql .= Sql::FROM . Sql::SQL_SPACE . $table->getName();
        $sql .= !empty($alias) ? Sql::SQL_SPACE.Sql:: AS.Sql::SQL_SPACE.$alias : null;

        return new Sql($sql);
    }
}
