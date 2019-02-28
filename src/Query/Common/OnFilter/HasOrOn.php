<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\OnFilter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Trait HasOrOn
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrOn
{
    private $on = false;

    /**
     * @param $field
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     * @throws InterfaceException
     */
    public function orOn($field, ?OperatorInterface $operator): OnFilterInterface
    {
        if ($this instanceof OnFilterInterface) {
            if ($field instanceof \Closure) {
                $field->call($this);
            } else if ($field instanceof FieldInterface) {
                $field->setAccent($this->query()->accent());

                $this->query()->sql()
                    ->append(Sql::OR)
                    ->append($field->sql()->queryString())
                    ->append($operator->sql());
            }

            $this->query()->appendSqlToClause(Sql::JOIN);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke OnFilterInterface in: %s.", get_class($this)));
        }
    }
}
