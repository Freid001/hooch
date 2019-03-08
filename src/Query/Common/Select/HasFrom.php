<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

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
     * @throws InterfaceException
     */
    public function from(RepositoryInterface $table): SelectInterface
    {
        if ($this instanceof SelectInterface) {
            $accent = $this->query()->accent();

            $this->table = $table;
            $this->setFilter($this->table->filter());

            $this->query()->clause(Sql::FROM, function (Sql $sql) use ($accent) {
                return $sql
                    ->append(Sql::FROM)
                    ->append($accent->append($this->table->getName()))
                    ->ifThenAppend(!empty($this->table->getAlias()), Sql:: AS)
                    ->ifThenAppend(!empty($this->table->getAlias()), $accent->append($this->table->getAlias()));
            });

            return $this;
        } else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
