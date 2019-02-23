<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql;

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
     * @return Sql
     */
    public function sql(): Sql
    {
        return $this->sql;
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
     * @param bool $hasSpace
     */
    public function appendSqlToClause(string $clause, $hasSpace = true): void
    {
        $this->appendString($clause, $this->sql->string(), !$hasSpace);
        $this->appendParameters($clause, $this->sql->parameters());

        $this->sql->reset();
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
     * @param bool $trim
     */
    private function appendString(string $clause, ?string $sql, bool $trim): void
    {
        if (!empty($this->query[$clause])) {
            if($trim){
                $this->query[$clause] = trim($this->query[$clause]) . $sql;
            }else {
                $this->query[$clause] .= $sql;
            }
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
