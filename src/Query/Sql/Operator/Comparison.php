<?php


namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\Sql\Sql;

/**
 * Class Comparison
 * @package QueryMule\Query\Sql\Operator
 */
class Comparison
{
    /**
     * @var Sql
     */
    private $sql;

    /**
     * @param $value
     * @return Comparison
     */
    public function equalTo($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = new Sql(Sql::SQL_EQUAL . Sql::SQL_SPACE . Sql::SQL_BRACKET_OPEN . Sql::SQL_SPACE . $value->sql() . Sql::SQL_SPACE .  Sql::SQL_BRACKET_CLOSE . Sql::SQL_SPACE, $value->parameters());
        } else {
            $this->sql = new Sql(Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK, [$value]);
        }

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function greaterThan($value): Comparison
    {
        $this->sql = new Sql(Sql::SQL_GREATER_THAN . Sql::SQL_QUESTION_MARK, [$value]);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThan($value): Comparison
    {
        $this->sql = new Sql(Sql::SQL_LESS_THAN . Sql::SQL_QUESTION_MARK, [$value]);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function greaterThanEqualTo($value): Comparison
    {
        $this->sql = new Sql(Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL . SQL::SQL_QUESTION_MARK, [$value]);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThanEqualTo($value): Comparison
    {
        $this->sql = new Sql(Sql::SQL_LESS_THAN . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK, [$value]);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function notEqualTo($value): Comparison
    {
        $this->sql = new Sql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN . Sql::SQL_QUESTION_MARK, [$value]);

        return $this;
    }

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }
}