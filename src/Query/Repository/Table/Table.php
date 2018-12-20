<?php

namespace QueryMule\Query\Repository\Table;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Operator\Operator;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class Table
 * @package QueryMule\Query\Repository\Table
 */
class Table extends AbstractTable
{
    /**
     * @var string
     */
    private $name;

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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}