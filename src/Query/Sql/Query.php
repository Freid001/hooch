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
     * @param $clause
     * @param Sql $sql
     * @return void
     */
    public function add($clause, Sql $sql): void
    {
        $this->sql[$clause] = !empty($this->sql[$clause]) ? $this->sql[$clause] . $sql->sql() : $sql->sql();

        foreach($sql->parameters() as $key => $parameter){
            $this->parameters[$clause][] = $parameter;
        }
    }

    /**
     * @param array $order
     * @return \QueryMule\Query\Sql\Sql
     */
    public function build(array $order): Sql
    {
        $sql = '';
        $parameters = [];
        foreach($order as $clause){
            if(!empty($this->sql[$clause])) {

                $sql .= !empty($sql) ? $this->sql[$clause] : $this->sql[$clause];
                if(!empty($this->parameters[$clause])) {
                    foreach ($this->parameters[$clause] as $parameter) {
                        $parameters[] = $parameter;
                    }
                }
            }
        }

        return new Sql($sql,$parameters,false);
    }

    /**
     * @param string $clause
     * @return string|null
     */
    public function get($clause): ?String
    {
        return !empty($this->sql[$clause]) ? $this->sql[$clause] : null;
    }

    /**
     * @param array $clauses
     * @return void
     */
    public function reset(array $clauses = []): void
    {
        if(empty($clauses)){
            $this->sql = [];
            $this->parameters = [];
        }

        foreach($clauses as $clause) {
            unset($this->sql[$clause]);
            unset($this->parameters[$clause]);
        }
    }
}
