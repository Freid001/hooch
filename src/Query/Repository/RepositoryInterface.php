<?php

namespace QueryMule\Query\Repository;

use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface RepositoryInterface
 * @package QueryMule\Query\Repository
 */
interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return FilterInterface
     */
    public function filter() : FilterInterface;
}