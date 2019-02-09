<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\InsertInterface;

/**
 * Trait HasInto
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasInto
{
    /**
     * @param RepositoryInterface $table
     * @param array $cols
     * @return InsertInterface
     * @throws SqlException
     */
    public function into(RepositoryInterface $table, array $cols): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $sql = $this->query()->sql();
            $sql->append(Sql::INTO);
            $sql->append($this->query()->accent()->append($table->getName()));
            $sql->append(Sql::SQL_BRACKET_OPEN);

            $query = $this->query();
            $sql->append(implode(",",
                array_map(function ($column) use ($query) {
                    return $query->accent()->append($column);
                }, $cols)
            ));

            $sql->append(Sql::SQL_BRACKET_CLOSE);

            $this->query()->append(Sql::INTO, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}
