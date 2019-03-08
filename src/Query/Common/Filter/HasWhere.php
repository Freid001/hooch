<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Trait HasWhere
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhere
{
    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function where(?FieldInterface $field, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $accent = $this->query()->accent();
            $fieldOperator = $this->operator()->field();
            $this->query()->clause(Sql::WHERE, function (Sql $sql) use ($field, $operator, $fieldOperator, $accent) {
                $sql->ifThenAppend($this->noClause(), $this->whereJoin())
                    ->ifThenAppend($this->isNested($operator), Sql::SQL_BRACKET_OPEN);

                if(!empty($field)) {
                    $field->setAccent($accent);

                    $sql->ifThenAppend($this->noClause(), $field->sql()->queryString());
                }

                if($this->isAnd($operator)) {
                    $sql->append($fieldOperator->and(
                        $field,
                        $operator
                    )->sql());
                }else {
                    $sql->append($operator->sql());
                }

                return $sql;
            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }

    /**
     * @param OperatorInterface $operator
     * @return bool
     */
    private function isNested(OperatorInterface $operator): bool
    {
        if($this instanceof FilterInterface) {
            if ($this->query()->hasClause($this->whereJoin())) {
                return false;
            }

            if (in_array($operator->operator(), [Sql:: AND, Sql:: OR])) {
                return false;
            }

            return $this->operator()->field()->isNested();
        }

        return false;
    }

    /**
     * @param OperatorInterface $operator
     * @return bool
     */
    private function isAnd(OperatorInterface $operator): bool
    {
        if ($this instanceof FilterInterface &&
                $this->query()->hasClause($this->whereJoin())) {

            if ($operator->operator() !== Sql:: OR) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function noClause(): bool
    {
        if ($this instanceof FilterInterface &&
            !$this->query()->hasClause($this->whereJoin())) {
                return true;
        }

        return false;
    }

    /**
     * @return string
     */
    private function whereJoin(): String
    {
        if ($this instanceof OnFilterInterface) {
            return Sql::JOIN;
        } else {
            return Sql::WHERE;
        }
    }
}
