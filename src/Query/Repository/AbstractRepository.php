<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Repository\Table;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Class AbstractTable
 * @package Redstraw\Hooch\Query\Repository\Table
 */
abstract class AbstractRepository implements RepositoryInterface
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
     * @return AbstractRepository
     */
    public function setAlias(?string $alias): AbstractRepository
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param array $cols
     * @return \Redstraw\Hooch\Query\Sql\Statement\SelectInterface|QueryBuilderInterface
     */
    public function select(array $cols = [Sql::SQL_STAR]): SelectInterface
    {
        return $this->driver->select()->cols($cols)->from($this);
    }

    /**
     * @return \Redstraw\Hooch\Query\Sql\Statement\FilterInterface
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
