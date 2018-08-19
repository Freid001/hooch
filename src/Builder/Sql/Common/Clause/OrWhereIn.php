<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\OrWhereInInterface;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereIn implements OrWhereInInterface
{
    /**
     * @var WhereInterface
     */
    private $clause;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * OrWhereBetween constructor.
     * @param OrWhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param array $values
     */
    public function orWhereIn($column, array $values = []): void
    {
        $this->clause->orWhere($column, null, $this->logical->in($values));
    }
}
