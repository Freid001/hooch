<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Driver;


use Psr\SimpleCache\CacheInterface;
use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Trait HasCache
 * @package QueryMule\Query\Common\Driver
 */
trait HasCache
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var null|integer
     */
    private $ttl;

    /**
     * @param CacheInterface $cache
     * @param int|null $ttl
     * @return DriverInterface|null
     */
    public function cache(CacheInterface $cache, ?int $ttl = null): ?DriverInterface
    {
        if($this instanceof DriverInterface){
            $this->cache = $cache;
            $this->ttl = $ttl;

            return $this;
        }

        return null;
    }
}
