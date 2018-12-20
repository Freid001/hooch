<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasFrom
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasFrom
{
    use Common;

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return $this
     */
    public function from(RepositoryInterface $table, ?string $alias = null)
    {
        if($this instanceof SelectInterface) {
            $this->setFilter($table->filter());
        }

        $this->query()->add(Sql::FROM, $this->fromClause($table, $alias));

        return $this;
    }

    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return Sql
     */
    private function fromClause(RepositoryInterface $table, $alias = null): Sql
    {
        $sql = new Sql();
        $sql->append(Sql::FROM);
        $sql->append($table->getName());
        $sql->ifThenAppend(!empty($alias), Sql:: AS);
        $sql->ifThenAppend(!empty($alias), $alias);

        return $sql;
    }
}
