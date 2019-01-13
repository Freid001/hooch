<?php

namespace QueryMule\Query\Connection\Driver;

use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Sql\Mysql\OnFilter;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class AdapterInterface
 * @package QueryMule\Query\Connection\Driver
 */
interface DriverInterface
{
    const DRIVER_MYSQL  = 'mysql';
    const DRIVER_PGSQL  = 'pgsql';
    const DRIVER_SQLITE = 'sqlite';
    const FETCH = 'fetch';
    const FETCH_ALL = 'fetchAll';

    /**
     * @return FilterInterface|null
     */
    public function filter() : ?FilterInterface;

    /**
     * @return OnFilterInterface|null
     */
    public function onFilter() : ?OnFilterInterface;

    /**
     * @return SelectInterface|null
     */
    public function select() : ?SelectInterface;

    /**
     * @param CacheInterface $cache
     * @param int|null $ttl
     * @return DriverInterface|null
     */
    public function cache(CacheInterface $cache, ?int $ttl = null) : ?DriverInterface;

    /**
     * @param Sql $sql
     * @return mixed
     */
    public function fetch(Sql $sql);

    /**
     * @param Sql $sql
     * @return mixed
     */
    public function fetchAll(Sql $sql);

    /**
     * @return string|null
     */
    public function driver(): ?string;

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(Sql $sql, string $method);
}
