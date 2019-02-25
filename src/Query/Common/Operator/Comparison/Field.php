<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Comparison;


use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class ComparisonColumn
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class Field implements OperatorInterface
{
    /**
     * @var
     */
    private $sql;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var string
     */
    private $operator = '';

    /**
     * Field constructor.
     * @param Sql $sql
     * @param Accent $accent
     */
    public function __construct(Sql $sql, Accent $accent)
    {
        $this->sql = $sql;
        $this->accent = $accent;
    }

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }

    /**
     * @return String
     */
    public function getOperator(): String
    {
        return $this->operator;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function equalTo(FieldInterface $field): Field
    {
        $field->setAccent($this->accent);

        $this->operator = Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function greaterThan(FieldInterface $field): Field
    {
        $this->operator = Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function greaterThanEqualTo(FieldInterface $field): Field
    {
        $field->setAccent($this->accent);

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function lessThan(FieldInterface $field): Field
    {
        $field->setAccent($this->accent);

        $this->operator = Sql::SQL_LESS_THAN;
        $this->sql
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function lessThanEqualTo(FieldInterface $field): Field
    {
        $field->setAccent($this->accent);

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return Field
     */
    public function notEqualTo(FieldInterface $field): Field
    {
        $field->setAccent($this->accent);

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;
        $this->sql
            ->append($this->operator)
            ->append($field->sql()->string(), [], false);

        return $this;
    }
}
