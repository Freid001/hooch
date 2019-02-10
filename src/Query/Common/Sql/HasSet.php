<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\UpdateInterface;

/**
 * Trait HasSet
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasSet
{
    /**
     * @param array $values
     * @return UpdateInterface
     * @throws SqlException
     */
    public function set(array $values): UpdateInterface
    {
        if($this instanceof UpdateInterface) {
            $sql = $this->query()->sql();
            $sql->ifThenAppend(empty($this->query()->hasClause(Sql::SET)),Sql::SET);
            $sql->ifThenAppend(!empty($this->query()->hasClause(Sql::SET)),",",[],false);

            $query = $this->query();
            $sql->append(implode(",",
                array_map(function ($column) use ($query) {
                    return $query->accent()->append($column) . Sql::SQL_SPACE . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK;
                }, array_keys($values))
            ), array_values($values), false);

            $this->query()->append(Sql::SET, $query->sql());

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
