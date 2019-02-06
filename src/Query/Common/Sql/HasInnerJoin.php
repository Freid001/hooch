<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasInnerJoin
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasInnerJoin
{
    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     * @throws SqlException
     */
    public function innerJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $this->join(Sql::JOIN_INNER, $table)->onFilter()->on($column, $operator);

            return $this;
        }else{
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}