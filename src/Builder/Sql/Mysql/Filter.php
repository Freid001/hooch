<?php

namespace QueryMule\Builder\Sql\Mysql;

use QueryMule\Builder\Sql\Common\Clause\HasOrWhereBetween;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereExists;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereNotIn;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereNotLike;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereIn;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereLike;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereNot;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereNotBetween;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhereNotExists;
use QueryMule\Builder\Sql\Common\Clause\HasWhere;
use QueryMule\Builder\Sql\Common\Clause\HasWhereBetween;
use QueryMule\Builder\Sql\Common\Clause\HasWhereExists;
use QueryMule\Builder\Sql\Common\Clause\HasWhereIn;
use QueryMule\Builder\Sql\Common\Clause\HasWhereLike;
use QueryMule\Builder\Sql\Common\Clause\HasWhereNot;
use QueryMule\Builder\Sql\Common\Clause\HasWhereNotBetween;
use QueryMule\Builder\Sql\Common\Clause\HasWhereNotExists;
use QueryMule\Builder\Sql\Common\Clause\HasWhereNotIn;
use QueryMule\Builder\Sql\Common\Clause\HasWhereNotLike;
use QueryMule\Builder\Sql\Common\Clause\HasNestedWhere;
use QueryMule\Builder\Sql\Common\Clause\HasOrWhere;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Mysql
 */
class Filter implements QueryBuilderInterface, FilterInterface
{
    use HasNestedWhere;
    use HasOrWhere;
    use HasOrWhereBetween;
    use HasOrWhereExists;
    use HasOrWhereIn;
    use HasOrWhereLike;
    use HasOrWhereNot;
    use HasOrWhereNotBetween;
    use HasOrWhereNotExists;
    use HasOrWhereNotIn;
    use HasOrWhereNotLike;
    use HasWhere;
    use HasWhereBetween;
    use HasWhereExists;
    use HasWhereIn;
    use HasWhereLike;
    use HasWhereNot;
    use HasWhereNotBetween;
    use HasWhereNotExists;
    use HasWhereNotIn;
    use HasWhereNotLike;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * Filter constructor.
     * @param Query $query
     * @param Logical $logical
     */
    public function __construct(Query $query, Logical $logical)
    {
        $this->query = $query;
        $this->logical = $logical;
        $this->accent = new Accent();
        $this->accent->setSymbol('`');
    }

    /**
     * @return Query
     */
    protected function query(): Query
    {
        return $this->query;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return $this->logical;
    }

    /**
     * @return Accent
     */
    protected function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::WHERE
    ]): Sql
    {
        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @param bool $ignore
     * @return $this|FilterInterface
     */
    public function ignoreAccent($ignore = true)
    {
        $this->accent->ignore($ignore);

        if (!empty($this->filter)) {
            $this->filter->accent()->ignore($ignore);
        }

        return $this;
    }
}
