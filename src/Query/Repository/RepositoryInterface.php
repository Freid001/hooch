<?php

declare(strict_types=1);

namespace QueryMule\Query\Repository;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Interface RepositoryInterface
 * @package QueryMule\Query\Repository
 */
interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getAlias(): ?string;

    /**
     * @param array $cols
     * @return \QueryMule\Query\Sql\Statement\SelectInterface|QueryBuilderInterface
     */
    public function select(array $cols = [Sql::SQL_STAR]) : SelectInterface;

    /**
     * @return FilterInterface
     */
    public function filter() : FilterInterface;

    /**
     * @return OnFilterInterface
     */
    public function onFilter() : OnFilterInterface;
}
