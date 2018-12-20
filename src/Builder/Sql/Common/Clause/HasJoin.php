<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasJoin
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasJoin
{
    use Common;

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return $this
     */
    public function join(string $type, RepositoryInterface $table, ?string $alias = null)
    {
        if($this instanceof SelectInterface) {
            $this->setOnFilter($table->onFilter());
        }

        $sql = new Sql();
        $sql->append($type);
        $sql->append($table->getName());
        $sql->ifThenAppend(!empty($alias), Sql:: AS);
        $sql->ifThenAppend(!empty($alias), $alias);

        $this->query()->append(Sql::JOIN, $sql);

        return $this;
    }
}