<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasGroupBy
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasGroupBy
{
    /**
     * @param FieldInterface $column
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function groupBy(FieldInterface $column): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $column->setAccent($this->query()->accent());

            $query = $this->query();
            $this->query()->clause(Sql::GROUP, function (Sql $sql) use ($query, $column) {
                return $sql
                    ->ifThenAppend(empty($query->hasClause(Sql::GROUP)), Sql::GROUP)
                    ->ifThenAppend(!empty($query->hasClause(Sql::GROUP)), ',', [], false)
                    ->append($column->sql()->queryString());
            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
