<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasFrom
 * @package QueryMule\Query\Common\Sql
 */
trait HasFrom
{
    /**
     * @param RepositoryInterface $table
     * @return SelectInterface
     * @throws SqlException
     */
    public function from(RepositoryInterface $table): SelectInterface
    {
        if ($this instanceof SelectInterface) {
            $this->setFilter($table->filter());

            $sql = $this->query()->sql();

            $sql->append(Sql::FROM)
                ->append($table->getName())
                ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
                ->ifThenAppend(!empty($table->getAlias()), $table->getAlias());

            $this->query()->append(Sql::FROM, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
