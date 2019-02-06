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
     * @return InsertInterface
     * @throws SqlException
     */
    public function into(RepositoryInterface $table): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $sql = $this->query()->sql();
            $sql->append(Sql::INTO);
            $sql->append($this->query()->accent()->append($table->getName()));

            $this->query()->append(Sql::INSERT, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}
