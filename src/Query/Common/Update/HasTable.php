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
            $query = $this->query();

            $this->table = $table;
            $this->setFilter($table->filter());

            $this->query()->clause(Sql::UPDATE, function (Sql $sql) use ($query) {
                return $sql
                    ->append($query->accent()->append($this->table->getName()))
                    ->ifThenAppend(!empty($this->table->getAlias()), Sql:: AS)
                    ->ifThenAppend(!empty($this->table->getAlias()), $query->accent()->append($this->table->getAlias()));
            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
