<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasGroupBy
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasGroupBy
{
    /**
     * @param FieldInterface $column
     * @return SelectInterface
     * @throws SqlException
     */
    public function groupBy(FieldInterface $column): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $column->setAccent($this->query()->accent());

            $this->query()->sql()
                ->ifThenAppend(empty($this->query()->hasClause(Sql::GROUP)), Sql::GROUP)
                ->ifThenAppend(!empty($this->query()->hasClause(Sql::GROUP)), ',', [], false)
                ->append($column->sql()->string());

            $this->query()->appendSqlToClause(Sql::GROUP);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
