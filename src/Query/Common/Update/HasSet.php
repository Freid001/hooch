<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Update;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\UpdateInterface;

/**
 * Trait HasSet
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasSet
{
    /**
     * @param array $values
     * @return UpdateInterface
     * @throws InterfaceException
     */
    public function set(array $values): UpdateInterface
    {
        if($this instanceof UpdateInterface) {
            $this->query()->sql()
                ->ifThenAppend(empty($this->query()->hasClause(Sql::SET)),Sql::SET)
                ->ifThenAppend(!empty($this->query()->hasClause(Sql::SET)),",",[],false);

            $query = $this->query();
            $this->query()->sql()->append(implode(",",
                array_map(function ($column) use ($query) {
                    return $query->accent()->append($column,true) . Sql::SQL_SPACE . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK;
                }, array_keys($values))
            ), array_values($values), false);

            $this->query()->appendSqlToClause(Sql::SET);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
