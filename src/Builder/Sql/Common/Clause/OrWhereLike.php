<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\OrWhereLikeInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereLike implements OrWhereLikeInterface
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
     * @param $value
     */
    public function orWhereLike($column, $value): void
    {
        $this->clause->orWhere($column, null, $this->logical->like($value));
    }
}
