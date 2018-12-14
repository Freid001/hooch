<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnInterface;

/**
 * Trait HasJoin
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasJoin
{
    use Common;

    /**
     * @return OnInterface
     */
    abstract protected function on(): OnInterface;

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param string $column
     * @param Comparison|null $comparison
     * @param Logical|null $logical
     * @return $this
     */
    public function join(string $type, RepositoryInterface $table, ?string $alias, string $column, ?Comparison $comparison, ?Logical $logical)
    {
        $sql = new Sql();
        $sql->append($type);
        $sql->append($table->getName());
        $sql->appendIf(!empty($alias),Sql:: AS . Sql::SQL_SPACE . $alias);

        if ($column instanceof \Closure) {
            call_user_func($column, $query = $this->on());
        }else {
            $sql->append(Sql::ON);
            $sql->append($column);
            $sql->appendIf(!is_null($comparison), $comparison);
            $sql->appendIf(!is_null($logical), $logical);
        }

        $this->query()->add(Sql::JOIN, $sql);

        return $this;
    }
}