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
     * @param $type
     * @return string|null
     */
    final protected function queryGet($type)
    {
        return !empty($this->sql[$type]) ? $this->sql[$type] : null;
    }

    /**
     * @param $type
     * @param Sql $sql
     */
    final protected function queryAdd($type, Sql $sql)
    {
        $this->sql[$type] = !empty($this->sql[$type]) ? $this->sql[$type] . ' ' . $sql->sql() : $sql->sql();

        foreach($sql->parameters() as $key => $parameter){
            $this->parameters[$type][] = $parameter;
        }
    }

    /**
     * @return void
     */
    final protected function queryReset(array $clauses)
    {
        foreach($clauses as $clause) {
            unset($this->sql[$clause]);
            unset($this->parameters[$clause]);
        }
    }

    /**
     * @param array $buildOrder
     * @return \QueryMule\Query\Sql\Sql
     */
    final protected function queryBuild(array $buildOrder)
    {
        $sql = '';
        $parameters = [];
        foreach($buildOrder as $type){
            if(!empty($this->sql[$type])) {

                $sql .= !empty($sql) ? ' '.$this->sql[$type] : $this->sql[$type];
                if(!empty($this->parameters[$type])) {
                    foreach ($this->parameters[$type] as $parameter) {
                        $parameters[] = $parameter;
                    }
                }
            }
        }
        return new Sql($sql,$parameters);
    }
}