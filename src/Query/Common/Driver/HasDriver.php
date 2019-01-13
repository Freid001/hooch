<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Driver;


use Psr\SimpleCache\CacheInterface;
use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Trait HasDriver
 * @package QueryMule\Query\Common\Driver
 */
trait HasDriver
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @return string|null
     */
    public function driver(): ?string
    {
        return $this->driver;
    }
}
