<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Logical;


use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class Field
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
     * @var bool
     */
    private $nested = false;

    /**
     * @var bool
     */
    private $trailingSpace = true;

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
        if ($this->getNested()) {
            $this->setNested(false);
        }

        $this->trailingSpace = true;

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
     * @return bool
     */
    public function getNested()
    {
        return $this->nested;
    }

    /**
     * @param bool $nested
     * @return $this
     */
    public function setNested(Bool $nested = false)
    {
        $this->nested = $nested;

        return $this;
    }

    /**
     * @return $this
     */
    public function omitTrailingSpace()
    {
        $this->trailingSpace = false;

        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return Field
     */
    public function and (?FieldInterface $field, OperatorInterface $operator): Field
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql:: AND;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN);

        if(!empty($field)) {
            $field->setAccent($this->accent);

            $this->sql->append($field->sql()->string());
        }

        $this->sql->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);

        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return Field
     */
    public function not(?FieldInterface $field, OperatorInterface $operator): Field
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql::NOT;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN);

        if(!empty($field)) {
            $field->setAccent($this->accent);

            $this->sql->append($field->sql()->string());
        }

        $this->sql->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);


        return $this;
    }

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return Field
     */
    public function or (?FieldInterface $field, OperatorInterface $operator): Field
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql::OR;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN);

        if(!empty($field)) {
            $field->setAccent($this->accent);

            $this->sql->append($field->sql()->string());
        }

        $this->sql->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);


        return $this;
    }
}
