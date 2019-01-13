<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;

/**
 * Class HasLogicalOperator
 * @package QueryMule\Query\Sql\Operator
 */
class Logical implements QueryBuilderInterface, OperatorInterface
{
    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var bool
     */
    private $nested;

    /**
     * @var bool
     */
    private $trailingSpace;

    /**
     * Logical constructor.
     */
    public function __construct()
    {
        $this->sql = new Sql();
        $this->trailingSpace = true;
    }

    public function query(): Query
    {
        return new Query(new Sql(), new Logical(), new Accent());
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
     * @param Sql $subQuery
     * @return Logical
     */
    public function all(Sql $subQuery): Logical
    {
        $this->operator = Sql::ALL;
        $this->sql = $this->operatorWithSubQuery($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return Logical
     */
    public function and ($column, OperatorInterface $operator): Logical
    {
        $this->operator = Sql:: AND;
        $this->sql = $this->operatorWithColumn($column, $operator);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function any(Sql $subQuery): Logical
    {
        $this->operator = Sql::ANY;
        $this->sql = $this->operatorWithSubQuery($subQuery);

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @return Logical
     */
    public function between($from, $to): Logical
    {
        $this->operator = Sql::BETWEEN;

        $sql = new Sql($this->operator);
        $sql->append(Sql::SQL_QUESTION_MARK, [$from]);
        $sql->append(Sql:: AND);
        $sql->append(Sql::SQL_QUESTION_MARK,[$to],$this->trailingSpace);

        $this->sql = $sql;

        return $this;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql
    {
        return $this->sql;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function exists(Sql $subQuery): Logical
    {
        $this->operator = Sql::EXISTS;
        $this->sql = $this->operatorWithSubQuery($subQuery);

        return $this;
    }

    /**
     * @return String|null
     */
    public function getOperator(): ?String
    {
        return $this->operator;
    }

    /**
     * @param array $values
     * @return Logical
     */
    public function in(array $values = []): Logical
    {
        $this->operator = Sql::IN;

        $sql = new Sql($this->operator);
        $sql->append(implode(Sql::SQL_SPACE, [
            Sql::SQL_BRACKET_OPEN,
            implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
            Sql::SQL_BRACKET_CLOSE
        ]), $values, $this->trailingSpace);

        $this->sql = $sql;

        return $this;
    }

    /**
     * @param $value
     * @return Logical
     */
    public function like($value): Logical
    {
        $this->operator = Sql::SQL_LIKE;

        $sql = new Sql($this->operator);
        $sql->append(Sql::SQL_QUESTION_MARK,[$value],false);

        $this->sql = $sql;

        return $this;
    }

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return Logical
     */
    public function not($column, OperatorInterface $operator): Logical
    {
        $this->operator = Sql::NOT;
        $this->sql = $this->operatorWithColumn($column, $operator);

        return $this;
    }

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return Logical
     */
    public function or ($column, OperatorInterface $operator): Logical
    {
        $this->operator = Sql::OR;
        $this->sql = $this->operatorWithColumn($column, $operator);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function some(Sql $subQuery): Logical
    {
        $this->operator = Sql::SOME;
        $this->sql = $this->operatorWithSubQuery($subQuery);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Sql
     */
    private function operatorWithSubQuery(Sql $subQuery): Sql
    {
        $sql = new Sql($this->operator, $subQuery->parameters());
        $sql->append(Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace);
        $sql->append($subQuery->string(), [], $this->trailingSpace);
        $sql->append(Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);

        return $sql;
    }

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return Sql
     */
    private function operatorWithColumn($column, OperatorInterface $operator): Sql
    {
        $sql = new Sql($this->operator);
        $sql->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN);
        $sql->append($column);
        $sql->ifThenAppend(!is_null($operator),$operator,[],$this->trailingSpace);

        if ($this->getNested()) {
            $this->setNested(false);
        }

        $this->trailingSpace = true;

        return $sql;
    }
}
