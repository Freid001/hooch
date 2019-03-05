<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Driver;

use Psr\Log\LoggerInterface;
use Redstraw\Hooch\Query\Common\Driver\HasOnFilter;
use Redstraw\Hooch\Query\Common\Driver\HasOperator;
use Redstraw\Hooch\Query\Common\Driver\HasSelect;
use Redstraw\Hooch\Query\Common\Driver\HasUpdate;
use Redstraw\Hooch\Query\Common\Driver\HasCache;
use Redstraw\Hooch\Query\Common\Driver\HasDriver;
use Redstraw\Hooch\Query\Common\Driver\HasFetch;
use Redstraw\Hooch\Query\Common\Driver\HasFetchAll;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Driver\HasFilter;
use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Query;
use Redstraw\Hooch\Query\Sql;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class PdoDriver
 * @package Redstraw\Hooch\Builder\Connection\Driver
 */
class PdoDriver implements DriverInterface
{
    use HasDriver;
    use HasQuery;
    use HasOperator;
    use HasCache;
    use HasFetch;
    use HasFetchAll;
    use HasFilter;
    use HasOnFilter;
    use HasSelect;
    use HasUpdate;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var Operator
     */
    private $operator;

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
    public function __construct(
        \PDO $pdo,
        Query $query,
        LoggerInterface $logger
    )
    {
        $this->pdo = $pdo;
        $this->driverName = (string)$this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $this->query = $query;
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|bool|mixed|null
     */
    public function execute(Sql $sql, string $method)
    {
        $result = null;
        try {
            $fromCache = false;
            $cacheKey = md5(serialize($sql));
            $time = microtime(true);

            if (!empty($this->cache) && !empty($this->cache->has($cacheKey))) {
                $fromCache = true;
                $result = json_decode((string)$this->cache->get($cacheKey));
            }else {
                $query = $this->pdo->prepare($sql->queryString());

                if (!$query->execute($sql->parameters())) {
                    $this->logger->error("PDO error code: " . $this->pdo->errorCode(), [
                        'query'     => $sql->queryString(),
                        'message'   => $this->pdo->errorInfo(),
                    ]);

                    return false;
                }

                $result = $this->getResult($query, $method, $cacheKey);
            }

            $this->query->reset();

            $this->logger->info("Successfully executed query",[
                'query'             => $sql->queryString(),
                'parameters'        => $sql->parameters(),
                'driver'            => $this->driverName(),
                'execution_time'    => round(microtime(true) - $time,4) . "s",
                'from_cache'        => $fromCache
            ]);
        }catch (InvalidArgumentException $e){
            $this->logger->error($e->getMessage());
        }

        return $result;
    }

    /**
     * @param \PDOStatement $stmt
     * @param string $method
     * @param string $cacheKey
     * @return array|mixed
     */
    private function getResult(\PDOStatement $stmt, string $method, string $cacheKey)
    {
        $result = null;
        try {
            switch ($method) {
                case DriverInterface::FETCH:
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    break;
                case DriverInterface::FETCH_ALL:
                    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    break;
            }

            if (!empty($this->cache)) {
                $this->cache->set($cacheKey, json_encode($result), $this->ttl);
            }
        }catch (InvalidArgumentException $e){
            $this->logger->error($e->getMessage());
        }

        return $result;
    }
}
