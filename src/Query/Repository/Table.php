<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Repository\Table;


use Redstraw\Hooch\Query\Driver\DriverInterface;

/**
 * Class Table
 * @package Redstraw\Hooch\Query\Repository\Table
 */
class Table extends AbstractRepository
{
    /**
     * Table constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        parent::__construct($driver);
    }

    /**
     * @param DriverInterface $driver
     * @return Table
     */
    public static function make(DriverInterface $driver)
    {
        return new self($driver);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}