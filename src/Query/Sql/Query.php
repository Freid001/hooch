<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql;

use Redstraw\Hooch\Query\Sql\Operator\Logical;

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
     * @var Logical
     */
    private $logical;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * Query constructor.
     * @param Sql $sql
     * @param Logical $logical
     * @param Accent $accent
     */
    public function __construct(Sql $sql, Logical $logical, Accent $accent)
    {
        $this->sql = $sql;
        $this->logical = $logical;
        $this->accent = $accent;
    }

    /**
     * @return Sql
     */
    public function sql(): Sql
    {
        return $this->sql;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return $this->logical;
    }

    /**
     * @return Accent
     */
    public function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @param string $clause
     * @param Sql $sql
     */
    public function append(string $clause, Sql $sql): void
    {
        $this->appendSql($clause, $sql->string());
        $this->appendParameters($clause, $sql->parameters());

        $sql->reset();
    }

    /**
     * @param array $order
     * @return Sql|null
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
     * @param $clause
     * @return bool
     */
    public function hasClause($clause): bool
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

    /**
     * @param string $clause
     * @param string|null $sql
     */
    private function appendSql(string $clause, ?string $sql): void
    {
        if (!empty($this->query[$clause])) {
            $this->query[$clause] .= $sql;
        } else {
            $this->query[$clause] = $sql;
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
