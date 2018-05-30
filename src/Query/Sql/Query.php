<?php

namespace QueryMule\Query\Sql;

/**
 * Class Query
 * @package QueryMule\Query\Sql
 */
trait Query
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
    final protected function queryAdd($clause, Sql $sql)
    {
        $this->sql[$clause] = !empty($this->sql[$clause]) ? $this->sql[$clause] . ' ' . $sql->sql() : $sql->sql();

        foreach($sql->parameters() as $key => $parameter){
            $this->parameters[$clause][] = $parameter;
        }
    }

    /**
     * @param array $order
     * @return \QueryMule\Query\Sql\Sql
     */
    final protected function queryBuild(array $order)
    {
        $sql = '';
        $parameters = [];
        foreach($order as $clause){
            if(!empty($this->sql[$clause])) {

                $sql .= !empty($sql) ? ' '.$this->sql[$clause] : $this->sql[$clause];
                if(!empty($this->parameters[$clause])) {
                    foreach ($this->parameters[$clause] as $parameter) {
                        $parameters[] = $parameter;
                    }
                }
            }
        }
        return new Sql($sql,$parameters);
    }

    /**
     * @param string $clause
     * @return string|null
     */
    final protected function queryGet($clause)
    {
        return !empty($this->sql[$clause]) ? $this->sql[$clause] : null;
    }

    /**
     * @param array $clauses
     * @return void
     */
    final protected function queryReset(array $clauses)
    {
        foreach($clauses as $clause) {
            unset($this->sql[$clause]);
            unset($this->parameters[$clause]);
        }
    }
}
