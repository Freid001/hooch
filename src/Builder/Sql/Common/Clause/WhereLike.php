<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereLikeInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereLike implements WhereLikeInterface
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
     * @param $value
     */
    public function whereLike($column, $value): void
    {
        $this->clause->where($column, null, $this->logical->like($value));
    }
}
