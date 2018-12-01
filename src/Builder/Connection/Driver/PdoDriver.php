<?php

namespace QueryMule\Builder\Connection\Driver;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class PdoDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class PdoDriver implements DriverInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var null|integer
     */
    private $ttl;

    /**
     * @var FilterInterface|null
     */
    private $filter;

    /**
     * @var SelectInterface|null
     */
    private $select;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PdoDriver constructor.
     * @param \PDO $pdo
     * @param LoggerInterface $logger
     */
    public function __construct(\PDO $pdo, LoggerInterface $logger)
    {
        $this->pdo = $pdo;
        $this->driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $this->logger = $logger;
    }

    /**
     * @return FilterInterface
     * @throws DriverException
     */
    public function filter() : FilterInterface
    {
        $this->filter = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $this->filter = new \QueryMule\Builder\Sql\Mysql\Filter();
                break;

            case self::DRIVER_PGSQL:
                $this->filter = new \QueryMule\Builder\Sql\Pgsql\Filter();
                break;

            case self::DRIVER_SQLITE:
                $this->filter = new \QueryMule\Builder\Sql\Sqlite\Filter();
                break;

            default:
                throw new DriverException(sprintf("Driver: %u not currently supported!",$this->driver));
        }

        return $this->filter;
    }

    /**
     * @param array $cols
     * @param RepositoryInterface|null $repository
     * @return SelectInterface
     * @throws DriverException
     */
    public function select(array $cols = [],RepositoryInterface $repository = null) : SelectInterface
    {
        $this->select = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $this->select = new \QueryMule\Builder\Sql\Mysql\Select($cols, $repository);
                break;

            case self::DRIVER_PGSQL:
                //$this->select = new \QueryMule\Builder\Sql\Pgsql\Select($cols, $repository);
                break;

            case self::DRIVER_SQLITE:
                //$this->select = new \QueryMule\Builder\Sql\Sqlite\Select($cols, $repository);
                break;

            default:
                throw new DriverException('Driver: '.$this->driver.' not currently supported');
        }

        return $this->select;
    }

    /**
     * @param string $type
     * @return null|FilterInterface|SelectInterface
     */
    public function getStatement($type)
    {
        $statement = null;
        switch ($type){
            case 'filter':
                $statement = $this->filter;
                break;

            case 'select':
                $statement = $this->select;
                break;
        }

        return $statement;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->filter = null;
        $this->select = null;
    }

    /**
     * @param CacheInterface $cache
     * @param integer $ttl
     * @return DriverInterface
     */
    public function cache(CacheInterface $cache, $ttl = null) : DriverInterface
    {
        $this->cache = $cache;
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @param Sql $sql
     * @return array|bool
     */
    public function fetch(Sql $sql)
    {
        return $this->execute($sql, 'fetch');
    }

    /**
     * @param Sql $sql
     * @return array|bool
     */
    public function fetchAll(Sql $sql)
    {
        return $this->execute($sql, 'fetch_all');
    }

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|bool|mixed
     */
    private function execute(Sql $sql, string $method)
    {
        $cache = false;
        $key = md5(serialize($sql));
        $time = microtime(true);

        if(empty($this->cache) || empty($this->cache->has($key))) {
            $query = $this->pdo->prepare($sql->sql());

            if (!$query || !$query->execute($sql->parameters())) {
                $this->logger->error("PDO error code: " . $this->pdo->errorCode(), [
                    'query' => $sql->sql(),
                    'message' => $this->pdo->errorInfo(),
                ]);

                return false;
            }

            $result = [];
            switch ($method){
                case 'fetch':
                    $result = $query->fetch();
                    break;
                case 'fetch_all':
                    $result = $query->fetchAll(\PDO::FETCH_ASSOC);
                    break;
            }

            if(!empty($this->cache)) {
                $this->cache->set($key, json_encode($result), $this->ttl);
            }
        }else {
            $cache = true;
            $result = json_decode($this->cache->get($key));
        }

        $this->reset();

        $this->logger->info("Successfully executed query",[
            'query'             => $sql->sql(),
            'parameters'        => $sql->parameters(),
            'driver'            => $this->driver,
            'execution_time'    => round(microtime(true) - $time,4) . "s",
            'from_cache'        => $cache
        ]);

        return $result;
    }
}
