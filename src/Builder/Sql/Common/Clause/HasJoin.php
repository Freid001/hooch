<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;

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
     * @param $column
     * @return $this
     */
    public function join(string $type, RepositoryInterface $table, ?string $alias, $column = null)
    {
        $sql = new Sql();
        $sql->append($type);
        $sql->append($table->getName());
        $sql->appendIf(!empty($alias),Sql:: AS . Sql::SQL_SPACE . $alias);

        if ($column instanceof \Closure) {
            call_user_func($column, $query = $this->onFilter());
        }else {
            $sql->appendIf(!is_null($column),Sql::ON);
            $sql->appendIf(!is_null($column),$column);
        }

        $this->query()->add(Sql::JOIN, $sql);

        return $this;
    }
}