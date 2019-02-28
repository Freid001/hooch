<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Update;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\UpdateInterface;

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
     * @throws InterfaceException
     */
    public function table(RepositoryInterface $table): UpdateInterface
    {
        if($this instanceof UpdateInterface) {
            $this->query()->sql()
                ->append($this->query()->accent()->append($table->getName()))
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

            $this->query()->appendSqlToClause(Sql::UPDATE);

            $this->table = $table;
            $this->setFilter($table->filter());

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
