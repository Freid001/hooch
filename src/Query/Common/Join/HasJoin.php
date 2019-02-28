<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Join;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\JoinInterface;

/**
 * Trait HasJoin
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasJoin
{
    private $joinTables = [];

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @return JoinInterface
     * @throws InterfaceException
     */
    public function join(string $type, RepositoryInterface $table): JoinInterface
    {
        if ($this instanceof JoinInterface) {
            $this->setOnFilter($table->onFilter());

            $this->query()->sql()
                ->append($type)
                ->append($this->query()->accent()->append($table->getName()))
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

            $this->query()->appendSqlToClause(Sql::JOIN);

            $this->joinTables[] = $table;

            return $this;
        } else {
            throw new InterfaceException(sprintf("Must invoke JoinInterface in: %s.", get_class($this)));
        }
    }
}
