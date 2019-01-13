<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasLeftJoin
 * @package QueryMule\Query\Common\Sql
 */
trait HasLeftJoin
{
    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     * @throws SqlException
     */
    public function leftJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $this->join(Sql::JOIN_LEFT, $table)->onFilter()->on($column, $operator);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}