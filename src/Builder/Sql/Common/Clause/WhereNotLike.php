<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereNotLikeInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class WhereNot
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereNotLike implements WhereNotLikeInterface
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
     * @param WhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param $values
     */
    public function whereNotLike($column, $values): void
    {
        $this->clause->whereNot($column, null, $this->logical->like($values));
    }
}
