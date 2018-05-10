<?php

namespace QueryMule\Builder\Connection\Driver;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class MysqliDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class MysqliDriver implements DriverInterface
{
    /**
     * @var \mysqli
     */
    private $mysqli;

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
     * MysqliDriver constructor.
     * @param \mysqli $mysqli
     * @param LoggerInterface $logger
     */
    public function __construct(\mysqli $mysqli, LoggerInterface $logger)
    {
        $this->mysqli = $mysqli;
        $this->logger = $logger;
    }

    /**
     * @return FilterInterface
     */
    public function filter() : FilterInterface
    {
        $this->filter = new Filter();

        return $this->filter;
    }

    /**
     * @param array $cols
     * @param RepositoryInterface|null $repository
     * @return SelectInterface
     */
    public function select(array $cols = [],RepositoryInterface $repository = null) : SelectInterface
    {
        $this->select = new Select($cols, $repository);

        return $this->select ;
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
     * @throws DriverException
     */
    public function fetch(Sql $sql)
    {
        return $this->execute($sql,'fetch');
    }

    /**
     * @param Sql $sql
     * @return array|bool
     * @throws DriverException
     */
    public function fetchAll(Sql $sql)
    {
        return $this->execute($sql,'fetch_all');
    }

    /**
     * @param Sql $sql
     * @param string $method
     * @return array|bool
     */
    private function execute(Sql $sql, string $method)
    {
        $cache = false;
        $key = md5(serialize($sql));
        $time = microtime(true);

        if(empty($this->cache) || empty($this->cache->has($key))) {
            $query = $this->mysqli->prepare($sql->sql());

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
                    'query' => $sql->sql(),
                    'message' => $this->mysqli->error
                ]);

                return false;
            }

            $result = [];
            switch ($method){
                case 'fetch':
                    $result = $query->get_result()->fetch_assoc();
                    break;
                case 'fetch_all':
                    $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
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
            'driver'            => self::DRIVER_MYSQL,
            'execution_time'    => round(microtime(true) - $time,4) . "s",
            'from_cache'        => $cache
        ]);

        return $result;
    }
}
