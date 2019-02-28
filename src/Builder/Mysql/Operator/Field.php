<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql\Operator;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\FieldOperatorInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class Field
 * @package Redstraw\Hooch\Builder\Sql\Mysql\Operator\Field
 */
class Field implements FieldOperatorInterface
{
    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var string
     */
    private $operator = '';

    /**
     * @var bool
     */
    private $nested = false;

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
     * @return Accent
     */
    public function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @return Sql
     */
    public function sql(): Sql
    {
        return $this->sql;
    }

    /**
     * @return string
     */
    public function operator(): string
    {
        return $this->operator;
    }

    /**
     * @param bool $nested
     */
    public function setNested(bool $nested): void
    {
        $this->nested = $nested;
    }

    /**
     * @return bool
     */
    public function isNested(): bool
    {
        return $this->nested;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return Field
     */
    public function and (?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface
    {
        $this->operator = Sql::AND;
        $this->logical($field, $operator);

        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    public function not(?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface
    {
        $this->operator = Sql::NOT;
        $this->logical($field, $operator);

        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    public function or (?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface
    {
        $this->operator = Sql::OR;
        $this->logical($field, $operator);

        return $this;
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function eq(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_EQUAL;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function notEq(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function gt(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function gtEq(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN.Sql::SQL_EQUAL;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function lt(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function ltEq(FieldInterface $field): FieldOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN.Sql::SQL_EQUAL;

        return $this->comparison($field);
    }

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    private function comparison(FieldInterface $field): FieldOperatorInterface
    {
        $field->setAccent($this->accent());

        $this->sql()
            ->reset()
            ->append($this->operator())
            ->append($field->sql()->queryString(), [], false);

        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    private function logical(?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface
    {
        $operatorString = $operator->sql()->queryString();
        $operatorParameters = $operator->sql()->parameters();

        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($this->isNested(), Sql::SQL_BRACKET_OPEN);

        if(!empty($field)) {
            $field->setAccent($this->accent);

            $this->sql->append($field->sql()->queryString());
        }

        $this->sql->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, false);

        $this->setNested(false);

        return $this;
    }
}
