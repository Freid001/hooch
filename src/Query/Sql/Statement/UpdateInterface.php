<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;

/**
 * Interface UpdateInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface UpdateInterface extends QueryBuilderInterface, JoinInterface
{
    /**
     * @param RepositoryInterface $table
     * @return UpdateInterface
     */
    public function table(RepositoryInterface $table): UpdateInterface;

    /**
     * @param array $cols
     * @return UpdateInterface
     */
    public function set(array $cols): UpdateInterface;

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function filter(\Closure $callback): UpdateInterface;

    /**
     * @param FilterInterface $filter
     * @return void
     */
    public function setFilter(FilterInterface $filter): void;

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function onFilter(\Closure $callback): UpdateInterface;

    /**
     * @param OnFilterInterface $onFilter
     * @return void
     */
    public function setOnFilter(OnFilterInterface $onFilter): void;
}