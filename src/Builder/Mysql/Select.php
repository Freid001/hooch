<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql;


use Redstraw\Hooch\Query\Common\HasOperator;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Select\HasCols;
use Redstraw\Hooch\Query\Common\Select\HasFrom;
use Redstraw\Hooch\Query\Common\Select\HasGroupBy;
use Redstraw\Hooch\Query\Common\Select\HasHaving;
use Redstraw\Hooch\Query\Common\Join\HasInnerJoin;
use Redstraw\Hooch\Query\Common\Join\HasJoin;
use Redstraw\Hooch\Query\Common\Join\HasLeftJoin;
use Redstraw\Hooch\Query\Common\Select\HasLimit;
use Redstraw\Hooch\Query\Common\Select\HasOffset;
use Redstraw\Hooch\Query\Common\Select\HasOrderBy;
use Redstraw\Hooch\Query\Common\Join\HasRightJoin;
use Redstraw\Hooch\Query\Common\Select\HasUnion;
use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Query;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\JoinInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Class Select
 * @package Redstraw\Hooch\Builder\Sql\Sqlite
 */
class Select implements SelectInterface
{
    use HasQuery;
    use HasOperator;
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
     * @param Operator $operator
     */
    public function __construct(Query $query, Operator $operator)
    {
        $this->operator = $operator;
        $this->query = $query;
        $this->query->clause(Sql::SELECT, function(Sql $sql){
            return $sql->append(Sql::SELECT);
        });
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
        if (in_array(Sql::WHERE, $clauses) && !empty($this->filter)) {
            $this->query->clause(Sql::WHERE, function(Sql $sql){
                return $sql->append($this->filter->build([Sql::WHERE]));
            });
        }

        if (in_array(Sql::JOIN, $clauses) && !empty($this->onFilter)) {
            $this->query->clause(Sql::JOIN, function(Sql $sql){
                return $sql->append($this->onFilter->build([Sql::JOIN]));
            });
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @param \Closure $callback
     * @return SelectInterface
     */
    public function filter(\Closure $callback): SelectInterface
    {
        if(!empty($this->filter)){
            $params = [];
            array_push($params, $this->filter);
            array_push($params, $this->table);
            array_map(function($table) use ($params){
                array_push($params, $table);
            }, $this->joinTables);
            call_user_func_array($callback, $params);
        }

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return JoinInterface
     */
    public function onFilter(\Closure $callback): JoinInterface
    {
        if(!empty($this->onFilter)){
            $params = [];
            array_push($params, $this->onFilter);
            call_user_func_array($callback, $params);
        }

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
