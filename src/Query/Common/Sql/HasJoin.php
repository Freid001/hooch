<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\JoinInterface;

/**
 * Trait HasJoin
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasJoin
{
    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @return JoinInterface
     * @throws SqlException
     */
    public function join(string $type, RepositoryInterface $table): JoinInterface
    {
        if($this instanceof JoinInterface) {
            $this->setOnFilter($table->onFilter());

            $sql = $this->query()->sql();

            $sql->append($type)
                ->append($this->query()->accent()->append($table->getName()))
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

            $this->query()->append(Sql::JOIN, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke JoinInterface in: %s.", get_class($this)));
        }
    }
}