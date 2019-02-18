<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasFrom
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasFrom
{
    /**
     * @var RepositoryInterface
     */
    private $table;

    /**
     * @param RepositoryInterface $table
     * @return SelectInterface
     * @throws SqlException
     */
    public function from(RepositoryInterface $table): SelectInterface
    {
        if ($this instanceof SelectInterface) {
            $this->setFilter($table->filter());

            $this->query()->sql()
                ->append(Sql::FROM)
                ->append($this->query()->accent()->append($table->getName()))
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

            $this->query()->toClause(Sql::FROM);

            $this->table = $table;

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
