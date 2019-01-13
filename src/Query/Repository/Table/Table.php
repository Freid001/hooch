<?php

declare(strict_types=1);

namespace QueryMule\Query\Repository\Table;


use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Class Table
 * @package QueryMule\Query\Repository\Table
 */
class Table extends AbstractTable
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