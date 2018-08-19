<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\WhereExistsInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Class WhereExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereExists implements WhereExistsInterface
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
     * WhereBetween constructor.
     * @param WhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param Sql $subQuery
     */
    public function whereExists(Sql $subQuery): void
    {
        $this->clause->where(null, null, $this->logical->exists($subQuery));
    }
}
