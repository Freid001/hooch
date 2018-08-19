<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\WhereInInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class WhereIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereIn implements WhereInInterface
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
     * @param WhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param array $values
     */
    public function whereIn($column, array $values = []): void
    {
        $this->clause->where($column, null, $this->logical->in($values));
    }
}
