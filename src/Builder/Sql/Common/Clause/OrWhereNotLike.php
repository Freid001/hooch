<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotLikeInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNotLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereNotLike implements OrWhereNotLikeInterface
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
     * WhereNot constructor.
     * @param OrWhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param $values
     */
    public function orWhereNotLike($column, $values): void
    {
        $this->clause->orWhereNot($column, null, $this->logical->like($values));
    }
}
