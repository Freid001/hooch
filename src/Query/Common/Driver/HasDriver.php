<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Psr\SimpleCache\CacheInterface;
use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;

/**
 * Trait HasDriver
 * @package Redstraw\Hooch\Query\Common\Driver
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
