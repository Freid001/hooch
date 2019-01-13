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
 * Class MysqliDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class MysqliDriver implements DriverInterface
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
     * @var \mysqli
     */
    private $mysqli;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MysqliDriver constructor.
     * @param \mysqli $mysqli
     * @param Query $query
     * @param LoggerInterface $logger
     */
    public function __construct(\mysqli $mysqli, Query $query, LoggerInterface $logger)
    {
        $this->mysqli = $mysqli;
        $this->driver = self::DRIVER_MYSQL;
        $this->query = $query;
        $this->logger = $logger;
    }

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(Sql $sql, string $method)
    {
        $cache = false;
        $key = md5(serialize($sql));
        $time = microtime(true);

        if (empty($this->cache) || empty($this->cache->has($key))) {
            $query = $this->mysqli->prepare($sql->string());

            $parameters = [0 => ''];
            foreach ($sql->parameters() as $parameter) {
                switch (gettype($parameter)) {
                    case 'integer':
                        $parameters[0] .= 'i';
                        break;

                    case 'double':
                        $parameters[0] .= 'd';
                        break;

                    case 'string':
                        $parameters[0] .= 's';
                        break;

                    default:
                        $parameters[0] .= 'b';
                        break;
                }

                $parameters[] = &$parameter;
            }

            call_user_func_array([$query, 'bind_param'], $parameters);

            if (!$query->execute()) {
                $this->logger->error("Mysqli error code: " . $this->mysqli->connect_errno, [
                    'query' => $sql->string(),
                    'message' => $this->mysqli->error
                ]);

                return false;
            }

            $result = [];
            switch ($method) {
                case DriverInterface::FETCH:
                    $result = $query->get_result()->fetch_assoc();
                    break;
                case DriverInterface::FETCH_ALL:
                    $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;
            }

            if (!empty($this->cache)) {
                $this->cache->set($key, json_encode($result), $this->ttl);
            }
        } else {
            $cache = true;
            $result = json_decode((string)$this->cache->get($key));
        }

        $this->select = null;

        $this->logger->info("Successfully executed query", [
            'query' => $sql->string(),
            'parameters' => $sql->parameters(),
            'driver' => self::DRIVER_MYSQL,
            'execution_time' => round(microtime(true) - $time, 4) . "s",
            'from_cache' => $cache
        ]);

        return $result;
    }
}
