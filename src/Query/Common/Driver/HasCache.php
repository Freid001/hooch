<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Psr\SimpleCache\CacheInterface;
use Redstraw\Hooch\Query\Driver\DriverInterface;

/**
 * Trait HasCache
 * @package Redstraw\Hooch\Query\Common\Driver
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
