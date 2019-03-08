<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query;

/**
 * Class Query
 * @package Redstraw\Hooch\Query\Sql
 */
class Query
{
    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * Query constructor.
     * @param Sql $sql
     * @param Accent $accent
     */
    public function __construct(Sql $sql, Accent $accent)
    {
        $this->sql = $sql;
        $this->accent = $accent;
    }

    /**
     * @param string $clause
     * @param \Closure $callback
     * @return Query
     */
    public function clause(string $clause, \Closure $callback): Query
    {
        $sql = $callback($this->sql);
        if(!$sql instanceof Sql) {
            return $this;
        }

        if (!empty($this->query[$clause])) {
            $this->query[$clause] .= trim($sql->queryString()) . Sql::SQL_SPACE;
            $this->parameters[$clause] = array_merge($this->parameters[$clause], $sql->parameters());

            $sql->reset();

            return $this;
        }

        $this->query[$clause] = trim($sql->queryString()) . Sql::SQL_SPACE;
        $this->parameters[$clause] = $sql->parameters();

        $sql->reset();

        return $this;
    }

    /**
     * @return Accent
     */
    public function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @param array $order
     * @return Sql
     */
    public function build(array $order = []): Sql
    {
        return array_reduce($order, function(Sql $sql, $clause){
            if($this->hasClause($clause)){
                $sql->append(
                    $this->query[$clause],
                    $this->parameters[$clause],
                    false
                );
            }

            return $sql;
        }, new Sql(null, [], false));
    }

    /**
     * @param string $clause
     * @return bool
     */
    public function hasClause(string $clause): bool
    {
        return !empty($this->query[$clause]);
    }

    /**
     * @param array $clauses
     * @return void
     */
    public function reset(array $clauses = []): void
    {
        if (empty($clauses)) {
            $this->query = [];
            $this->parameters = [];
        }

        foreach ($clauses as $clause) {
            unset($this->query[$clause]);
            unset($this->parameters[$clause]);
        }
    }
}
