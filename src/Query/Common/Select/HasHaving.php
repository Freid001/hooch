<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasHaving
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasHaving
{
    /**
     * @param FieldInterface $field
     * @param OperatorInterface $operator
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function having(FieldInterface $field, OperatorInterface $operator): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $field->setAccent($this->query()->accent());

            $this->query()->sql()
                ->append(Sql::HAVING)
                ->append($field->sql()->queryString())
                ->append($operator->sql());

            $this->query()->appendSqlToClause(Sql::HAVING);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
