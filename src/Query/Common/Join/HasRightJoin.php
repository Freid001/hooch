<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Join;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\JoinInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Trait HasRightJoin
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasRightJoin
{
    /**
     * @param RepositoryInterface $table
     * @param FieldInterface|\Closure $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface
     * @throws InterfaceException
     */
    public function rightJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface
    {
        if($this instanceof JoinInterface) {
            $this->join(Sql::JOIN_RIGHT, $table)->onFilter(function(OnFilterInterface $f) use($column, $operator) {
                $f->on($column, $operator);
            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke JoinInterface in: %s.", get_class($this)));
        }
    }
}
