<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasOrOn
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrOn
{
    private $on = false;

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     * @throws SqlException
     */
    public function orOn($column, ?OperatorInterface $operator): OnFilterInterface
    {
        if ($this instanceof OnFilterInterface) {
            $sql = $this->query()->sql();

            if ($column instanceof \Closure) {
                $this->on($column, $operator);
            }else {
                $sql->ifThenAppend(!is_null($column),Sql::OR);
                $sql->ifThenAppend(!is_null($column),$column);
            }

            $sql->append($operator);

            $this->query()->append(Sql::JOIN, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke OnFilterInterface in: %s.", get_class($this)));
        }
    }
}
