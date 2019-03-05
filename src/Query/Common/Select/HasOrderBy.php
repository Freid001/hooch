<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasOrderBy
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrderBy
{
    /**
     * @param FieldInterface $field
     * @param string $order
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function orderBy(FieldInterface $field, string $order = SQL::DESC): SelectInterface
    {
        if($this instanceof SelectInterface){
            $field->setAccent($this->query()->accent());

            $this->query()->sql()
                ->ifThenAppend(!$this->query()->hasClause(Sql::ORDER), Sql::ORDER)
                ->ifThenAppend(!$this->query()->hasClause(Sql::ORDER), Sql::BY)
                ->ifThenAppend($this->query()->hasClause(Sql::ORDER), ',' , [], false)
                ->append($field->sql()->queryString())
                ->append(strtoupper($order));

            $this->query()->appendSqlToClause(Sql::ORDER);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
