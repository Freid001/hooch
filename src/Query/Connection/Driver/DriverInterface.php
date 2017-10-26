<?php

namespace QueryMule\Query\Connection\Driver;

use Psr\SimpleCache\CacheInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class AdapterInterface
 * @package QueryMule\Adapter
 */
interface DriverInterface
{
    const DRIVER_MYSQL  = 'mysql';
    const DRIVER_PGSQL  = 'pgsql';
    const DRIVER_SQLITE = 'sqlite';

    /**
     * @return FilterInterface
     */
    public function filter() : FilterInterface;

    /**
     * @param array $cols
     * @param RepositoryInterface $table
     * @return SelectInterface
     */
    public function select(array $cols = [],RepositoryInterface $table = null) : SelectInterface;

    /**
     * @param CacheInterface $cache
     * @param null $ttl
     * @return DriverInterface
     */
    public function cache(CacheInterface $cache, $ttl = null) : DriverInterface;

    /**
     * @param Sql $sql
     */
    public function fetch(Sql $sql);

    /**
     * @param Sql $sql
     */
    public function fetchAll(Sql $sql);
}