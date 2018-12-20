<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql;

/**
 * Class Query
 * @package QueryMule\Query\Sql
 */
class Query
{
    /**
     * @var array
     */
    protected $sql = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param string $clause
     * @param Sql $sql
     */
    public function append(string $clause, Sql $sql): void
    {
        $this->appendSql($clause, $sql->sql());
        $this->appendParameters($clause, $sql->parameters());
    }

    /**
     * @param array $order
     * @return Sql|null
     */
    public function build(array $order = []): Sql
    {
        return array_reduce($order, function(Sql $sql, $clause){
            $sql->append(
                $this->getSql($clause),
                $this->getParameters($clause),
                false
            );

            return $sql;
        }, new Sql(null, [], false));
    }

    /**
     * @param string $clause
     * @return string|null
     */
    public function getSql($clause): ?String
    {
        if (!empty($this->sql[$clause])) {
            return $this->sql[$clause];
        }

        return null;
    }

    /**
     * @param $clause
     * @return array
     */
    public function getParameters($clause): array
    {
        if (!empty($this->parameters[$clause])) {
            return $this->parameters[$clause];
        }

        return [];
    }

    /**
     * @param array $clauses
     * @return void
     */
    public function reset(array $clauses = []): void
    {
        if (empty($clauses)) {
            $this->sql = [];
            $this->parameters = [];
        }

        foreach ($clauses as $clause) {
            unset($this->sql[$clause]);
            unset($this->parameters[$clause]);
        }
    }

    /**
     * @param string $clause
     * @param string|null $sql
     */
    private function appendSql(string $clause, ?string $sql): void
    {
        if (!empty($this->sql[$clause])) {
            $this->sql[$clause] .= $sql;
        } else {
            $this->sql[$clause] = $sql;
        }
    }

    /**
     * @param string $clause
     * @param array $parameters
     * @return void
     */
    private function appendParameters(string $clause, array $parameters): void
    {
        if (!empty($this->parameters[$clause])) {
            $this->parameters[$clause] = array_merge($this->parameters[$clause], $parameters);
        } else {
            $this->parameters[$clause] = $parameters;
        }
    }
}
