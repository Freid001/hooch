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
     * TableInterface constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return FilterInterface
     */
    public function getFilter() : FilterInterface;
}