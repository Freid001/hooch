<?php

namespace QueryMule\Builder\Connection\Driver;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
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
     * @var null|int
     */
    private $ttl;

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
        $filter = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $filter = new \QueryMule\Builder\Sql\Mysql\Filter();
                break;

            case self::DRIVER_PGSQL:
                $filter = new \QueryMule\Builder\Sql\Pgsql\Filter();
                break;

            case self::DRIVER_SQLITE:
                $filter = new \QueryMule\Builder\Sql\Sqlite\Filter();
                break;

            default:
                throw new DriverException('Driver: '.$this->driver.' not currently supported');
        }

        return $filter;
    }

    /**
     * @param array $cols
     * @param RepositoryInterface|null $table
     * @return SelectInterface
     * @throws DriverException
     */
    public function select(array $cols = [],RepositoryInterface $table = null) : SelectInterface
    {
        $select = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $select = new \QueryMule\Builder\Sql\Mysql\Select($cols, $table);
                break;

            case self::DRIVER_PGSQL:
                $select = new \QueryMule\Builder\Sql\Pgsql\Select($cols, $table);
                break;

            case self::DRIVER_SQLITE:
                $select = new \QueryMule\Builder\Sql\Sqlite\Select($cols, $table);
                break;

            default:
                throw new DriverException('Driver: '.$this->driver.' not currently supported');
        }

        return $select;
    }

    /**
     * @param CacheInterface $cache
     * @param int $ttl
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
     * @return array
     */
    public function fetch(Sql $sql)
    {
        return $this->execute($sql, 'fetch');
    }

    /**
     * @param Sql $sql
     * @return array
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