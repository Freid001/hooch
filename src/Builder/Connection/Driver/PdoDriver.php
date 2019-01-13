<?php

declare(strict_types=1);

namespace QueryMule\Builder\Connection\Driver;

use Psr\Log\LoggerInterface;
use QueryMule\Builder\Common\Statement\HasOnFilter;
use QueryMule\Builder\Common\Statement\HasSelect;
use QueryMule\Query\Common\Driver\HasCache;
use QueryMule\Query\Common\Driver\HasDriver;
use QueryMule\Query\Common\Driver\HasFetch;
use QueryMule\Query\Common\Driver\HasFetchAll;
use QueryMule\Query\Common\HasQuery;
use QueryMule\Query\Common\Statement\HasFilter;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;

/**
 * Class PdoDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class PdoDriver implements DriverInterface
{
    use HasDriver;
    use HasQuery;
    use HasCache;
    use HasFetch;
    use HasFetchAll;
    use HasFilter;
    use HasOnFilter;
    use HasSelect;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PdoDriver constructor.
     * @param \PDO $pdo
     * @param Query $query
     * @param LoggerInterface $logger
     */
    public function __construct(\PDO $pdo, Query $query, LoggerInterface $logger)
    {
        $this->pdo = $pdo;
        $this->driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $this->query = $query;
        $this->logger = $logger;
    }

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|bool|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(Sql $sql, string $method)
    {
        $cache = false;
        $key = md5(serialize($sql));
        $time = microtime(true);

        if(empty($this->cache) || empty($this->cache->has($key))) {
            $query = $this->pdo->prepare($sql->string());

            if (!$query || !$query->execute($sql->parameters())) {
                $this->logger->error("PDO error code: " . $this->pdo->errorCode(), [
                    'query' => $sql->string(),
                    'message' => $this->pdo->errorInfo(),
                ]);

                return false;
            }

            $result = [];
            switch ($method){
                case DriverInterface::FETCH:
                    $result = $query->fetch();
                    break;
                case DriverInterface::FETCH_ALL:
                    $result = $query->fetchAll(\PDO::FETCH_ASSOC);
                    break;
            }

            if(!empty($this->cache)) {
                $this->cache->set($key, json_encode($result), $this->ttl);
            }
        }else {
            $cache = true;
            $result = json_decode((string)$this->cache->get($key));
        }

        $this->select = null;

        $this->logger->info("Successfully executed query",[
            'query'             => $sql->string(),
            'parameters'        => $sql->parameters(),
            'driver'            => $this->driver,
            'execution_time'    => round(microtime(true) - $time,4) . "s",
            'from_cache'        => $cache
        ]);

        return $result;
    }
}
