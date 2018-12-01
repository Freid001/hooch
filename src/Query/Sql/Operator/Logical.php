<?php

namespace QueryMule\Query\Sql\Operator;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Class HasLogicalOperator
 * @package QueryMule\Query\Sql\Operator
 */
class Logical implements QueryBuilderInterface
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
        $this->sql = new Sql(null);
        $this->trailingSpace = true;
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
        $this->sql = $this->operatorWithSubQuery(Sql::ALL, $subQuery);

        $this->operator = Sql::ALL;

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function and ($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $this->sql = $this->operatorWithColumn(Sql:: AND, $column, $comparison, $logical);

        $this->operator = Sql:: AND;

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function any(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::ANY, $subQuery);

        $this->operator = Sql::ANY;

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @return Logical
     */
    public function between($from, $to): Logical
    {
        $sql = new Sql(Sql::BETWEEN, [$from, $to]);
        $sql->append(Sql::SQL_QUESTION_MARK);
        $sql->append(Sql:: AND);
        $sql->append(Sql::SQL_QUESTION_MARK,[],$this->trailingSpace);

        $this->sql = $sql;

        $this->operator = Sql::BETWEEN;

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
        $this->sql = $this->operatorWithSubQuery(Sql::EXISTS, $subQuery);

        $this->operator = Sql::EXISTS;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param array $values
     * @return Logical
     */
    public function in(array $values = []): Logical
    {
        $sql = new Sql(Sql::IN, $values);
        $sql->append(implode(Sql::SQL_SPACE, [
            Sql::SQL_BRACKET_OPEN,
            implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
            Sql::SQL_BRACKET_CLOSE
        ]), [], $this->trailingSpace);

        $this->sql = $sql;

        $this->operator = Sql::IN;

        return $this;
    }

    /**
     * @param $value
     * @return Logical
     */
    public function like($value): Logical
    {
        $sql = new Sql(Sql::SQL_LIKE, [$value]);
        $sql->append(Sql::SQL_QUESTION_MARK,[],false);

        $this->sql = $sql;

        $this->operator = Sql::SQL_LIKE;

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function not($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $this->sql = $this->operatorWithColumn(Sql::NOT, $column, $comparison, $logical);

        $this->operator = Sql::NOT;

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function or ($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $this->sql = $this->operatorWithColumn(Sql::OR, $column, $comparison, $logical);

        $this->operator = Sql::OR;

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function some(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::SOME, $subQuery);

        $this->operator = Sql::SOME;

        return $this;
    }

    /**
     * @param string $operator
     * @param Sql $subQuery
     * @return Sql
     */
    private function operatorWithSubQuery(string $operator, Sql $subQuery): Sql
    {
        $sql = new Sql($operator, $subQuery->parameters());
        $sql->append(Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace);
        $sql->append($subQuery->sql(), [], $this->trailingSpace);
        $sql->append(Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);

        return $sql;
    }

    /**
     * @param string $operator
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Sql
     */
    private function operatorWithColumn(string $operator, $column, ?Comparison $comparison, ?Logical $logical): Sql
    {
        $sql = new Sql($operator);
        $sql->appendIf($this->nested, Sql::SQL_BRACKET_OPEN);
        $sql->append($column);
        $sql->appendIf(!is_null($comparison),$comparison,[],$this->trailingSpace);
        $sql->appendIf(!is_null($logical),$logical,[],$this->trailingSpace);

        if ($this->getNested()) {
            $this->setNested(false);
        }

        $this->trailingSpace = true;

        return $sql;
    }
}
