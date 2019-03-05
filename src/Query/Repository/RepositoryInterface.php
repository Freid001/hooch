<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Repository;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Interface RepositoryInterface
 * @package Redstraw\Hooch\Query\Repository
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
    public function getAlias(): string;

    /**
     * @param array $cols
     * @return SelectInterface
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
