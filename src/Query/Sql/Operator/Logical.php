<?php

namespace QueryMule\Sql\Operator;
use QueryMule\Builder\Sql\Generic\Filter;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;


/**
 * Class Comparison
 * @package QueryMule\Builder\Sql\Generic
 */
class Logical
{
    /**
     * @var null|string
     */
    private $operator = null;

    /**
     * @param $operator
     */
    private function __construct($operator)
    {
        $this->operator = $operator;
    }

    public static function in(array $value = [])
    {
        $sql = FilterInterface::IN;
        $sql .= SelectInterface::SQL_SPACE
        $sql .= implode(SelectInterface::SQL_SPACE, [
            SelectInterface::SQL_BRACKET_OPEN,
            implode( ",", array_fill(0, count($value), "?")),
            SelectInterface::SQL_BRACKET_CLOSE
        ]);

        return new self($sql);
    }

    public static function not(FilterInterface $filter)
    {

    }

    public function build()
    {
        return $this->operator;
    }
}