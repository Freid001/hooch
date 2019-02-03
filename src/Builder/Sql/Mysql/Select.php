<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Common\HasQuery;
use QueryMule\Query\Common\Sql\HasCols;
use QueryMule\Query\Common\Sql\HasFrom;
use QueryMule\Query\Common\Sql\HasFullOuterJoin;
use QueryMule\Query\Common\Sql\HasGroupBy;
use QueryMule\Query\Common\Sql\HasHaving;
use QueryMule\Query\Common\Sql\HasInnerJoin;
use QueryMule\Query\Common\Sql\HasJoin;
use QueryMule\Query\Common\Sql\HasLeftJoin;
use QueryMule\Query\Common\Sql\HasLimit;
use QueryMule\Query\Common\Sql\HasOffset;
use QueryMule\Query\Common\Sql\HasOrderBy;
use QueryMule\Query\Common\Sql\HasRightJoin;
use QueryMule\Query\Common\Sql\HasUnion;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
class Select implements SelectInterface
{
    use HasQuery;
    use HasCols;
    use HasFrom;
    use HasGroupBy;
    use HasLimit;
    use HasOffset;
    use HasUnion;
    use HasOrderBy;
    use HasJoin;
    use HasLeftJoin;
    use HasRightJoin;
    use HasInnerJoin;
    use HasFullOuterJoin;
    use HasHaving;

    /**
     * @var FilterInterface|QueryBuilderInterface
     */
    private $filter;

    /**
     * @var OnFilterInterface|QueryBuilderInterface
     */
    private $onFilter;

    /**
     * Select constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
        $this->query->append(Sql::SELECT, $this->query->sql()->append(Sql::SELECT));
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::SELECT,
        Sql::COLS,
        Sql::FROM,
        Sql::JOIN,
        Sql::WHERE,
        Sql::GROUP,
        Sql::ORDER,
        Sql::HAVING,
        Sql::LIMIT,
        Sql::OFFSET,
        Sql::UNION
    ]): Sql
    {
        if (in_array(Sql::WHERE, $clauses) && $this->filter) {
            $this->query->append(Sql::WHERE, $this->filter->build([Sql::WHERE]));
        }

        if (in_array(Sql::JOIN, $clauses) && $this->onFilter) {
            $this->query->append(Sql::JOIN, $this->onFilter->build([Sql::JOIN]));
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @return FilterInterface|null
     */
    public function filter(): ?FilterInterface
    {
        return $this->filter;
    }

    /**
     * @return OnFilterInterface|null
     */
    public function onFilter(): ?OnFilterInterface
    {
        return $this->onFilter;
    }

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true): SelectInterface
    {
        $this->query->accent()->ignore($ignore);

        return $this;
    }

    /**
     * @param FilterInterface $filter
     */
    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @param OnFilterInterface $onFilter
     */
    public function setOnFilter(OnFilterInterface $onFilter): void
    {
        $this->onFilter = $onFilter;
    }
}