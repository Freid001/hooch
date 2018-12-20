<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasInnerJoin
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasInnerJoin
{
    use Common;

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @param OperatorInterface|null $operator
     * @return $this
     */
    public function innerJoin(RepositoryInterface $table, ?string $alias, $column, ?OperatorInterface $operator = null)
    {
        if($this instanceof SelectInterface) {
            $this->join(Sql::JOIN_INNER, $table, $alias)->onFilter()->on($column, $operator);
        }

        return $this;
    }
}