<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\JoinInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasFullOuterJoin
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasFullOuterJoin
{
    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface
     * @throws SqlException
     */
    public function fullOuterJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface
    {
        if($this instanceof JoinInterface) {
            $this->join(Sql::JOIN_FULL_OUTER, $table)->onFilter(function() use($column, $operator) {
                /** @var OnFilterInterface $this */
                $this->on($column, $operator);
            });

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke JoinInterface in: %s.", get_class($this)));
        }
    }
}