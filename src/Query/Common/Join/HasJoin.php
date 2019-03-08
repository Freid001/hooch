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
    /**
     * @var array
     */
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
            $accent = $this->query()->accent();

            $this->joinTables[] = $table;
            $this->setOnFilter($table->onFilter());

            $this->query()->clause(Sql::JOIN, function (Sql $sql) use ($type, $accent, $table) {
                return $sql->append($type)
                    ->append($accent->append($table->getName()))
                    ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                    ->ifThenAppend(!empty($table->getAlias()), $accent->append($table->getAlias()));
            });

            return $this;
        } else {
            throw new InterfaceException(sprintf("Must invoke JoinInterface in: %s.", get_class($this)));
        }
    }
}
