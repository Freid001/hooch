<?php

declare(strict_types=1);

namespace QueryMule\Query\Repository\Table;


use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class AbstractTable
 * @package QueryMule\Query\Repository\Table
 */
abstract class AbstractTable implements RepositoryInterface
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var OnFilterInterface
     */
    protected $onFilter;

    /**
     * @var SelectInterface
     */
    protected $select;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * AbstractTable constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string|null $alias
     * @return AbstractTable
     */
    public function setAlias(?string $alias): AbstractTable
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param array $cols
     * @return \QueryMule\Query\Sql\Statement\SelectInterface|QueryBuilderInterface
     */
    public function select(array $cols = [Sql::SQL_STAR]): SelectInterface
    {
        return $this->driver->select()->cols($cols)->from($this);
    }

    /**
     * @return \QueryMule\Query\Sql\Statement\FilterInterface
     */
    public function filter(): FilterInterface
    {
        return $this->driver->filter();
    }

    /**
     * @return OnFilterInterface
     */
    public function onFilter(): OnFilterInterface
    {
        return $this->driver->onFilter();
    }
}
