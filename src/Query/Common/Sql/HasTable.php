<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\UpdateInterface;

/**
 * Trait HasTable
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasTable
{
    /**
     * @var RepositoryInterface
     */
    private $table;

    /**
     * @param RepositoryInterface $table
     * @return UpdateInterface
     * @throws SqlException
     */
    public function table(RepositoryInterface $table): UpdateInterface
    {
        if($this instanceof UpdateInterface) {
            $sql = $this->query()->sql();
            $sql->append($this->query()->accent()->append($table->getName()))
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

            $this->query()->append(Sql::UPDATE, $sql);

            $this->table = $table;
            $this->setFilter($table->filter());

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
