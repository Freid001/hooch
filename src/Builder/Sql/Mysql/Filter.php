<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Common\HasQuery;
use QueryMule\Query\Common\Sql\HasNestedWhere;
use QueryMule\Query\Common\Sql\HasOrWhere;
use QueryMule\Query\Common\Sql\HasOrWhereBetween;
use QueryMule\Query\Common\Sql\HasOrWhereExists;
use QueryMule\Query\Common\Sql\HasOrWhereIn;
use QueryMule\Query\Common\Sql\HasOrWhereLike;
use QueryMule\Query\Common\Sql\HasOrWhereNot;
use QueryMule\Query\Common\Sql\HasOrWhereNotBetween;
use QueryMule\Query\Common\Sql\HasOrWhereNotExists;
use QueryMule\Query\Common\Sql\HasOrWhereNotIn;
use QueryMule\Query\Common\Sql\HasOrWhereNotLike;
use QueryMule\Query\Common\Sql\HasWhere;
use QueryMule\Query\Common\Sql\HasWhereBetween;
use QueryMule\Query\Common\Sql\HasWhereExists;
use QueryMule\Query\Common\Sql\HasWhereIn;
use QueryMule\Query\Common\Sql\HasWhereLike;
use QueryMule\Query\Common\Sql\HasWhereNot;
use QueryMule\Query\Common\Sql\HasWhereNotBetween;
use QueryMule\Query\Common\Sql\HasWhereNotExists;
use QueryMule\Query\Common\Sql\HasWhereNotIn;
use QueryMule\Query\Common\Sql\HasWhereNotLike;
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
class Filter implements FilterInterface
{
    use HasQuery;
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
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
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
}
